<?php

namespace App\Http\Helpers;

use App\Models\ProcessType;
use Illuminate\Support\Facades\Storage;

class UploadFilesHelper
{
    /**
     * Checks if the given temporary submission contains an excel file.
     *
     * @param  string  $submissionCode Submission directory to check
     * @return bool
     */
    public function containsExcel($submissionCode)
    {
        $files = Storage::disk('local')->files('files/temp/'.$submissionCode);

        foreach ($files as $fileName) {
            if (str_contains(strtolower($fileName), '.xlsx')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the designated excel sheet for a temporary submission.
     *
     * @param  string  $submissionCode Submission directory to check
     * @return string Excel sheet directory
     */
    public function getSubmissionExcel($submissionCode)
    {
        for ($i = 0; $i < 2; $i++) {
            sleep(2); // wait 2 seconds for file to finish uploading and try again

            $files = Storage::disk('local')->files('files/temp/'.$submissionCode);

            foreach ($files as $fileName) {
                if (str_contains(strtolower($fileName), '.xlsx')) {
                    return $fileName;
                }
            }

            // try one more time
        }

        return '';
    }

    /**
     * Gets the filename for given file directory and submission code.
     *
     * @param  string  $file Directory of the file
     * @param  string  $submissionCode Submission directory to check
     * @return string Filename
     */
    public function getFileName($file, $submissionCode)
    {
        return substr(explode($submissionCode, $file)[1], 1);
    }

    /**
     * Gets the response for the headings Returns true if the headings are correct.
     *
     * @param  array  $matrix Matrix of the excel sheet
     * @return array [Headings correct - Boolean , Response - array]
     */
    public function checkHeadings($matrix)
    {
        $headingsCorrect = true;
        $response = [];
        $expectedHeadings = config('excel-template');
        $headings = $matrix[1];

        foreach ($expectedHeadings as $heading => $headingData) {
            if (! in_array($heading, $headings)) {
                $headingsCorrect = false;
                $response[] = "Heading '$heading' not found";
            }
        }

        return [
            $headingsCorrect,
            $response,
        ];
    }

    /**
     * Cleans the document matrix to a more readable array.
     *
     * @param  array  $matrix Matrix of the excel sheet
     * @return array [Heading Name - [values]]
     */
    public function cleanMatrix($matrix)
    {
        $headings = config('excel-template');
        $headingMatrix = [];
        foreach ($headings as $heading => $headingData) {
            $index = array_search($heading, $matrix[1]);
            $headingMatrix[$heading] = $index;
        }

        $skip = 2;
        $counter = 0;
        $newMatrix = [];

        foreach ($matrix as $row) {
            $counter++;

            if ($counter <= $skip) {
                continue;
            }

            $allEmpty = true;
            foreach ($headings as $heading => $headingData) {
                $newMatrix[$heading][] = $row[$headingMatrix[$heading]];

                if (! empty($row[$headingMatrix[$heading]])) {
                    $allEmpty = false;
                    // if all the fields for this row is empty, treat it as the last row
                }

            }

            if ($allEmpty) {
                break;
            }

        }

        return $newMatrix;
    }

    /**
     * Checks the data integrity of the rows. Returns false with an error
     * message if there is a wrong data type somewhere. Returns true with
     * the converted values for the matrix if everything is valid.
     *
     * @param  array  $matrix Matrix of the excel sheet
     * @return array
     */
    public function checkDataIntegrity($matrix)
    {
        $headings = config('excel-template');

        for ($i = 0; $i < count($matrix['Item Number']); $i++) {
            // if last item number isn't null do all the below
            if (empty($matrix['Item Number'][$i])) {
                break;
            }

            foreach ($headings as $heading => $headingData) {

                if (! empty($headingData)) {

                    // if manufactured or purchased column value is "-"
                    // change it to "purchased"
                    if (strtolower($matrix['Manufactured or Purchased'][$i]) == '-') {
                        $matrix['Manufactured or Purchased'][$i] = 'purchased';
                    }

                    // set manufactured or purchased column to manufactured
                    // when the part name starts with PPM...
                    if (
                        ! empty($matrix['File Name'][$i])
                        && strtolower(substr($matrix['File Name'][$i], 0, 3)) == 'ppm'
                        && strtolower($matrix['Manufactured or Purchased'][$i]) != 'purchased then manufactured'
                    ) {
                        $matrix['Manufactured or Purchased'][$i] = 'Manufactured';
                    }

                    // if weldment is blank change it to "no"
                    if (empty($matrix['Used In Weldment'][$i])) {
                        $matrix['Used In Weldment'][$i] = 'No';
                    }

                    // require overrides
                    if (
                        strtolower($matrix['Manufactured or Purchased'][$i]) == 'purchased'
                        && ($heading == 'Process Type' || $heading == 'Material')
                    ) {
                        $headingData['required'] = false;
                    }
                    if (
                        str_contains(strtolower($matrix['Process Type'][$i]), 'l')
                        && ($heading == 'Material Thickness')
                    ) {
                        $headingData['required'] = false;
                    }

                    // check required
                    if (
                        ! empty($headingData['required']) && $headingData['required'] // if required
                        && empty($matrix[$heading][$i]) // and value is empty
                    ) {
                        return [
                            false,
                            'Data integrity violation:',
                            ['Row '.($i + 3)." , heading '$heading' is required. Please fill in."],
                        ];
                    }

                    if ($heading == 'File Name') {
                        // check that file names contains .par or .psm
                        if (
                            ! (str_contains($matrix[$heading][$i], '.par')
                            || str_contains($matrix[$heading][$i], '.psm'))
                        ) {
                            return [
                                false,
                                'Data integrity violation:',
                                ['Row '.($i + 3)." , file name '".$matrix[$heading][$i]."' must contain '.psm' or '.par'."],
                            ];
                        }

                        // if new BOM and file name starts with PPM, make sure that the last part isn't a digit
                        if (
                            // if new BOM
                            request()->get('submission_type') == 2 &&
                            // and part name starts with "PPM"
                            strtolower(substr($matrix[$heading][$i], 0, 3)) == 'ppm'
                        ) {
                            // and the last part is a digit
                            $partName = explode('-', $matrix[$heading][$i]);
                            $partName = end($partName);
                            $partName = explode('.', $partName)[0];
                            if (is_numeric($partName)) {
                                return [
                                    false,
                                    'Data integrity violation:',
                                    ['Row '.($i + 3)." , part name '".$matrix[$heading][$i]."' should not end with a digit."],
                                ];
                            }
                        }
                    }

                    // check numeric values
                    if (
                        ! empty($headingData['type']) && $headingData['type'] == 'int' // if int
                        && ! is_numeric($matrix[$heading][$i]) // and not a number
                    ) {
                        return [
                            false,
                            'Data integrity violation:',
                            ['Row '.($i + 3)." , heading '$heading' doesn't have a numeric value:
                            '".$matrix[$heading][$i]."'"],
                        ];
                    }

                    // check forced values
                    if (
                        ! empty($headingData['required']) && $headingData['required'] // if required
                        && ! empty($headingData['allowed']) // and has allowed value
                    ) {
                        if ($headingData['allowed'] == '/App\Models\ProcessType::class') {
                            $processTypes = array_map('strtolower', ProcessType::all()->pluck('process_type')->toArray());
                            if (! in_array(strtolower($matrix[$heading][$i]), $processTypes)) {
                                return [
                                    false,
                                    'Data integrity violation - Row '.($i + 3)." , heading '$heading' must contain
                                    one of the following:",
                                    $processTypes,
                                ];
                            }
                        } elseif (! in_array(strtolower($matrix[$heading][$i]), $headingData['allowed'])) {
                            return [
                                false,
                                'Data integrity violation - Row '.($i + 3)." , heading '$heading' must contain
                                one of the following:",
                                $headingData['allowed'],
                            ];
                        }
                    }

                    // convert values to correct state
                    if (! empty($headingData['type']) && $headingData['type'] == 'int') {
                        $matrix[$heading][$i] = (int) $matrix[$heading][$i];
                    } elseif ($heading == 'Used In Weldment') {
                        $matrix[$heading][$i] = (string) strtolower($matrix[$heading][$i]);
                    } elseif ($heading == 'Process Type') {
                        $matrix[$heading][$i] = (string) strtoupper($matrix[$heading][$i]);
                    }

                }

            }

        }

        return [true, $matrix, ''];
    }

    public function checkDuplicates($matrix)
    {
        $headings = config('excel-template');

        foreach ($headings as $heading => $headingData) {
            if (! empty($headingData['unique']) && $headingData['unique']) {
                $result = array_diff_assoc($matrix[$heading], array_unique($matrix[$heading]));
                if (! empty($result)) {
                    $filename = reset($result);
                    $indexes = array_keys($matrix[$heading], $filename);
                    $rows = [];
                    foreach ($indexes as $index) {
                        $rows[] = 'Row '.($index + 3);
                    }

                    return [
                        false,
                        "Duplicate values in column '$heading' found for $filename on the following rows:",
                        $rows,
                    ];
                }
            }
        }

        return [true, '', ''];
    }

    public function getRequiredFiles($matrix)
    {
        $files = [];

        for ($i = 0; $i < count($matrix['Item Number']); $i++) {
            if (
                ! empty($matrix['File Name'][$i]) &&
                ! empty($matrix['Process Type'][$i]) &&
                ! empty($processType = ProcessType::where('process_type', $matrix['Process Type'][$i])->first())
            ) {
                foreach (explode(',', $processType->required_files) as $file) {
                    $files[] = $matrix['File Name'][$i].' - '.strtoupper($file);
                }
            }
        }

        return $files;
    }

    public function getFileMatches($matrix, $submissionCode)
    {
        $requiredFiles = $this->getRequiredFiles($matrix);
        $files = Storage::disk('local')->files('files/temp/'.$submissionCode);
        $feedback = [];

        foreach ($requiredFiles as $requiredFile) {
            $split = str_contains($requiredFile, '.par - ') ? '.par - ' : '.psm - ';
            [$fileName, $fileType] = explode($split, $requiredFile);
            $fileName = str_replace(' ', '', $fileName);
            $fileType = strtolower($fileType);
            // check if above file exists
            foreach ($files as $file) {
                $file = explode("$submissionCode/", $file)[1];
                $file = str_replace(' ', '', $file);
                switch ($fileType) {
                    case 'step':
                        $exists = ($file == "$fileName.stp") || ($file == "$fileName.step");
                        break;
                    case 'dxf':
                        $exists = ($file == "$fileName.dxf") || ($file == "{$fileName}_R.dxf");
                        break;
                    case 'dwg':
                        $exists = ($file == "$fileName.dwg") || ($file == "{$fileName}_R.dwg");
                        break;
                    case 'pdf/dwg':
                        $exists = ($file == "$fileName.pdf") || ($file == "$fileName.dwg") || ($file == "{$fileName}_R.dwg");
                        break;
                    case 'pdf/step':
                        $exists = ($file == "$fileName.pdf") || ($file == "$fileName.step") || ($file == "{$fileName}.stp");
                        break;
                    case 'pdf/dxf':
                        $exists = ($file == "$fileName.pdf") || ($file == "$fileName.dxf") || ($file == "{$fileName}_R.dxf");
                        break;
                    case 'dwg/step':
                        $exists = ($file == "$fileName.dwg") || ($file == "$fileName.step") || ($file == "{$fileName}_R.dwg") || ($file == "{$fileName}.stp");
                        break;
                    case 'dwg/dxf':
                        $exists = ($file == "$fileName.dwg") || ($file == "$fileName.dxf") || ($file == "{$fileName}_R.dxf") || ($file == "{$fileName}_R.dwg");
                        break;
                    case 'step/dxf':
                        $exists = ($file == "$fileName.stp") || ($file == "$fileName.step") || ($file == "$fileName.dxf") || ($file == "{$fileName}_R.dxf");
                        break;
                    default:
                        $exists = $file == "$fileName.$fileType";
                }

                if ($exists) {
                    break;
                }
            }

            $feedback[] = $exists ?
            [
                'text' => "$requiredFile ($file)",
                'checked' => 'true',
                'color' => 'text-gray-400',
            ] :
            [
                'text' => "$requiredFile",
                'checked' => 'false',
                'color' => 'text-white',
            ];

        }

        return [$this->allFilesChecked($feedback), $feedback];
    }

    public function allFilesChecked($fileMatches)
    {
        foreach ($fileMatches as $file) {
            if ($file['checked'] == 'false') {
                return false;
            }
        }

        return true;
    }

    public function getAssemblyName($matrix)
    {
        $assemblyName = '';
        foreach ($matrix[0] as $column) {
            if (! empty($column)) {
                $assemblyName = $column;
            }
        }

        return trim(str_replace('Summary of Atomic Parts For', '', $assemblyName));
    }
}
