<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Job_history;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Shared\ZipArchive;
use Spatie\QueryBuilder\QueryBuilder;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\SimpleType\TblWidth;

use function Laravel\Prompts\error;

class WordController extends Controller
{
    public function mhd()
    {
        $data['message'] = 'mhd';
        return response()->json($data, 200);
    }
    public function generateWord(Request $request)
    {

        $file = '';
        if ($request->docname == 'ezdan') {
            $file = storage_path('app/public/Broker agreement_sama_ezdan.docx');
        }
        if ($request->docname == 'mayas') {
            $file = storage_path('app/public/Broker_agreement_sama_mayas.docx');
        }

        if ($file !== '') {
            // $file = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('file.docx'));

            // Load the template
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($file);

            // Replace placeholders with actual values
            $templateProcessor->setValue('license', $request->license);
            $templateProcessor->setValue('num', $request->num);
            $templateProcessor->setValue('postmailnum', $request->postmailnum);
            $templateProcessor->setValue('mobile', $request->mobile);
            $templateProcessor->setValue('phone', $request->phone);
            $templateProcessor->setValue('email', $request->email);
            $templateProcessor->setValue('address', $request->address);
            $templateProcessor->setValue('managername', $request->managername);
            $templateProcessor->setValue('nationality', $request->nationality);
            $templateProcessor->setValue('uaeid', $request->uaeid);
            $templateProcessor->setValue('emailmanager', $request->emailmanager);
            $templateProcessor->setValue('addressen', $request->addressen);
            $templateProcessor->setValue('managernameen', $request->managernameen);
            $templateProcessor->setValue('nationalityen', $request->nationalityen);
            $templateProcessor->setValue('companynameen', $request->companynameen);
            $templateProcessor->setValue('companyname', $request->companyname);
            $templateProcessor->setValue('city', $request->city);
            $templateProcessor->setValue('cityen', $request->cityen);
            $templateProcessor->setValue('job', $request->job);
            $templateProcessor->setValue('joben', $request->joben);


            // Save the modified document
            $fileName = 'modified_document333335.docx';
            if ($request->docname == 'ezdan') {
                $fileName = 'Broker agreement - sama ezdan.docx';
            }
            if ($request->docname == 'mayas') {
                $fileName = 'Broker agreement - sama mayas.docx';
            }
            $templateProcessor->saveAs($fileName);


            // Return the document as a download
            return response()->download($fileName)->deleteFileAfterSend(false);
        } else {
            return response()->json('Please select the correct file for company', 200);
        }
    }


    public function generateCvWord(Request $request)
    {
        $employee = Employee::find($request->employee_id);

        $emp = new EmployeeResource($employee);
        $jobs = QueryBuilder::for(Job_history::class)
            ->where('employee_id', $request->employee_id)->get();

        $file = '';

        $file = storage_path('app/public/cv.docx');



        $firstRowStyle = array('bgColor' => '66BBFF');



        $table = new Table(array('borderSize' => 8, 'borderColor' => 'black', 'width' => 9000, 'unit' => TblWidth::TWIP, 'alignment' => 'right'));
        // $table = new Table(array('unit' => TblWidth::TWIP));

        $table->addRow(50, $firstRowStyle);
        $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('تاريخ المباشرة');

        $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('الراتب الإجمالي');
        $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('الراتب الأساسي');
        $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('المنصب');
        $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('القسم');

        foreach ($jobs as $job) {
            $table->addRow(50, $firstRowStyle);
            $jobObject = Job::find($job->job_id);
            $departmentObject = Department::find($job->department_id);
            $table->addCell(1500)->addText($job->start_date);

            $table->addCell(1500)->addText($job->total_salary);
            $table->addCell(1500)->addText($job->basic_salary);

            $table->addCell(1500)->addText($jobObject->name);
            $table->addCell(1500)->addText($departmentObject->name);
        }


        if ($file !== '') {
            // $file = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('file.docx'));

            // Load the template
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($file);

            // Replace placeholders with actual values
            $templateProcessor->setValue('company', $emp->company->companyName);
            $templateProcessor->setValue('country', $emp->company->location);
            $templateProcessor->setValue('compay_address', $emp->company->address);
            $templateProcessor->setValue('self_num', $emp->self_number);
            $templateProcessor->setValue('passport', $emp->passport_number);
            $templateProcessor->setValue('insurance_num', $emp->insurance_number);
            $templateProcessor->setValue('full_name', $emp->first_name . ' ' . $emp->last_name);
            $templateProcessor->setValue('father', $emp->father_name);
            $templateProcessor->setValue('mother', $emp->mother_name);
            $templateProcessor->setValue('birth_date', $emp->birth_date);
            $templateProcessor->setValue('birth_place', $emp->birth_place);
            $templateProcessor->setValue('uae_id', $emp->uaeid_number);
            $templateProcessor->setValue('gender', $emp->gender);
            $templateProcessor->setValue('nationality', $emp->nationality);
            $templateProcessor->setValue('language', $emp->language);
            $templateProcessor->setValue('study', $emp->education);
            $templateProcessor->setValue('courses', $emp->courses);
            $templateProcessor->setValue('address', $emp->addressincompany->address_name);
            $templateProcessor->setValue('work_type', $emp->contract_type);
            $templateProcessor->setValue('card_start', $emp->hire_date);
            $templateProcessor->setValue('card_num', $emp->workcard_number);
            $templateProcessor->setValue('card_end', $emp->end_date);
            $templateProcessor->setValue('card_visa', $emp->hire_date);
            $templateProcessor->setValue('file_num', $emp->file_number);
            $templateProcessor->setValue('visa_end', $emp->visa_expiry);





            // $templateProcessor->setValue('table', $table);

            // Save the modified document
            $fileName = $employee->first_name . ' ' . $employee->last_name . '_' . date("d-m-yy_h:i:sa") . '_cv.docx';
            $templateProcessor->setComplexBlock('{table}', $table);

            $templateProcessor->saveAs($fileName);


            // Return the document as a download
            return response()->download($fileName)->deleteFileAfterSend(false);
        } else {
            return response()->json('Please select the correct file', 200);
        }
    }


    public function generateMultiCvWord(Request $request)
    {


        $files = new Collection([]);
        foreach ($request->ids as $employee_id) {
            $employee = Employee::find($employee_id);

            $emp = new EmployeeResource($employee);
            $jobs = QueryBuilder::for(Job_history::class)
                ->where('employee_id', $employee_id)->get();

            $file = '';

            $file = storage_path('app/public/cv.docx');



            $firstRowStyle = array('bgColor' => '66BBFF');



            $table = new Table(array('borderSize' => 8, 'borderColor' => 'black', 'width' => 9000, 'unit' => TblWidth::TWIP, 'alignment' => 'right'));
            // $table = new Table(array('unit' => TblWidth::TWIP));

            $table->addRow(50, $firstRowStyle);
            $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('تاريخ المباشرة');

            $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('الراتب الإجمالي');
            $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('الراتب الأساسي');
            $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('المنصب');
            $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('القسم');

            foreach ($jobs as $job) {
                $table->addRow(50, $firstRowStyle);
                $jobObject = Job::find($job->job_id);
                $departmentObject = Department::find($job->department_id);
                $table->addCell(1500)->addText($job->start_date);

                $table->addCell(1500)->addText($job->total_salary);
                $table->addCell(1500)->addText($job->basic_salary);

                $table->addCell(1500)->addText($jobObject->name);
                $table->addCell(1500)->addText($departmentObject->name);
            }


            if ($file !== '') {
                // $file = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('file.docx'));

                // Load the template
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($file);

                // Replace placeholders with actual values
                $templateProcessor->setValue('company', $emp->company->companyName);
                $templateProcessor->setValue('country', $emp->company->location);
                $templateProcessor->setValue('compay_address', $emp->company->address);
                $templateProcessor->setValue('self_num', $emp->self_number);
                $templateProcessor->setValue('passport', $emp->passport_number);
                $templateProcessor->setValue('insurance_num', $emp->insurance_number);
                $templateProcessor->setValue('full_name', $emp->first_name . ' ' . $emp->last_name);
                $templateProcessor->setValue('father', $emp->father_name);
                $templateProcessor->setValue('mother', $emp->mother_name);
                $templateProcessor->setValue('birth_date', $emp->birth_date);
                $templateProcessor->setValue('birth_place', $emp->birth_place);
                $templateProcessor->setValue('uae_id', $emp->uaeid_number);
                $templateProcessor->setValue('gender', $emp->gender);
                $templateProcessor->setValue('nationality', $emp->nationality);
                $templateProcessor->setValue('language', $emp->language);
                $templateProcessor->setValue('study', $emp->education);
                $templateProcessor->setValue('courses', $emp->courses);
                $templateProcessor->setValue('address', $emp->addressincompany->address_name);
                $templateProcessor->setValue('work_type', $emp->contract_type);
                $templateProcessor->setValue('card_start', $emp->hire_date);
                $templateProcessor->setValue('card_num', $emp->workcard_number);
                $templateProcessor->setValue('card_end', $emp->end_date);
                $templateProcessor->setValue('card_visa', $emp->hire_date);
                $templateProcessor->setValue('file_num', $emp->file_number);
                $templateProcessor->setValue('visa_end', $emp->visa_expiry);





                // $templateProcessor->setValue('table', $table);

                // Save the modified document
                $fileName = $employee->first_name . ' ' . $employee->last_name . '_' . date("d-m-yy_h:i:sa") . '_cv.docx';
                $templateProcessor->setComplexBlock('{table}', $table);

                $templateProcessor->saveAs($fileName);



                $files->push($fileName);
                // Return the document as a download
                // return response()->download($fileName)->deleteFileAfterSend(false);
            } else {
                return response()->json('Please select the correct file', 200);
            }
        }

        $zip = new ZipArchive;
        $zipFileName = 'employeesCVs_'. date("d-m-yy_h:i:sa").'.zip';

        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
            $filesToZip =$files;

            foreach ($filesToZip as $file) {
                $zip->addFile($file, basename($file));
            }

            $zip->close();

            return response()->download(public_path($zipFileName))->deleteFileAfterSend(false);
        } else {
            return "Failed to create the zip file.";
        }
       
        
       
    }


    public function generateAlliCvWord(Request $request)
    {


        $files = new Collection([]);

        $employees = QueryBuilder::for(Employee::class)->get();
        foreach ($employees as $employee) {
           

            $emp = new EmployeeResource($employee);
            $jobs = QueryBuilder::for(Job_history::class)
                ->where('employee_id', $employee->id)->get();

            $file = '';

            $file = storage_path('app/public/cv.docx');



            $firstRowStyle = array('bgColor' => '66BBFF');



            $table = new Table(array('borderSize' => 8, 'borderColor' => 'black', 'width' => 9000, 'unit' => TblWidth::TWIP, 'alignment' => 'right'));
            // $table = new Table(array('unit' => TblWidth::TWIP));

            $table->addRow(50, $firstRowStyle);
            $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('تاريخ المباشرة');

            $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('الراتب الإجمالي');
            $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('الراتب الأساسي');
            $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('المنصب');
            $table->addCell(1500, array('bgColor' => '#b1b1b1', 'valign' => 'center'))->addText('القسم');

            foreach ($jobs as $job) {
                $table->addRow(50, $firstRowStyle);
                $jobObject = Job::find($job->job_id);
                $departmentObject = Department::find($job->department_id);
                $table->addCell(1500)->addText($job->start_date);

                $table->addCell(1500)->addText($job->total_salary);
                $table->addCell(1500)->addText($job->basic_salary);

                $table->addCell(1500)->addText($jobObject->name);
                $table->addCell(1500)->addText($departmentObject->name);
            }


            if ($file !== '') {
                // $file = new \PhpOffice\PhpWord\TemplateProcessor(storage_path('file.docx'));

                // Load the template
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($file);

                // Replace placeholders with actual values
                $templateProcessor->setValue('company', $emp->company->companyName);
                $templateProcessor->setValue('country', $emp->company->location);
                $templateProcessor->setValue('compay_address', $emp->company->address);
                $templateProcessor->setValue('self_num', $emp->self_number);
                $templateProcessor->setValue('passport', $emp->passport_number);
                $templateProcessor->setValue('insurance_num', $emp->insurance_number);
                $templateProcessor->setValue('full_name', $emp->first_name . ' ' . $emp->last_name);
                $templateProcessor->setValue('father', $emp->father_name);
                $templateProcessor->setValue('mother', $emp->mother_name);
                $templateProcessor->setValue('birth_date', $emp->birth_date);
                $templateProcessor->setValue('birth_place', $emp->birth_place);
                $templateProcessor->setValue('uae_id', $emp->uaeid_number);
                $templateProcessor->setValue('gender', $emp->gender);
                $templateProcessor->setValue('nationality', $emp->nationality);
                $templateProcessor->setValue('language', $emp->language);
                $templateProcessor->setValue('study', $emp->education);
                $templateProcessor->setValue('courses', $emp->courses);
                $templateProcessor->setValue('address', $emp->addressincompany ? $emp->addressincompany->address_name : "");
                $templateProcessor->setValue('work_type', $emp->contract_type);
                $templateProcessor->setValue('card_start', $emp->hire_date);
                $templateProcessor->setValue('card_num', $emp->workcard_number);
                $templateProcessor->setValue('card_end', $emp->end_date);
                $templateProcessor->setValue('card_visa', $emp->hire_date);
                $templateProcessor->setValue('file_num', $emp->file_number);
                $templateProcessor->setValue('visa_end', $emp->visa_expiry);





                // $templateProcessor->setValue('table', $table);

                // Save the modified document
                $fileName = $employee->first_name . ' ' . $employee->last_name . '_' . date("d-m-yy_h:i:sa") . '_cv.docx';
                $templateProcessor->setComplexBlock('{table}', $table);

                $templateProcessor->saveAs($fileName);



                $files->push($fileName);
                // Return the document as a download
                // return response()->download($fileName)->deleteFileAfterSend(false);
            } else {
                return response()->json('Please select the correct file', 200);
            }
        }

        $zip = new ZipArchive;
        $zipFileName = 'employeesCVs_'. date("d-m-yy_h:i:sa").'.zip';

        if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
            $filesToZip =$files;

            foreach ($filesToZip as $file) {
                $zip->addFile($file, basename($file));
            }

            $zip->close();

            return response()->download(public_path($zipFileName))->deleteFileAfterSend(false);
        } else {
            return "Failed to create the zip file.";
        }
       
        
       
    }


    public function makedoc(Request $request)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        /* Note: any element you append to a document must reside inside of a Section. */

        // Adding an empty Section to the document...
        $section = $phpWord->addSection();
        // Adding Text element to the Section having font styled by default...
        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
                . 'The important thing is not to stop questioning." '
                . '(Albert Einstein)'
        );

        /*
         * Note: it's possible to customize font style of the Text element you add in three ways:
         * - inline;
         * - using named font style (new font style object will be implicitly created);
         * - using explicitly created font style object.
         */

        // Adding Text element with font customized inline...
        $section->addText(
            '"Great achievement is usually born of great sacrifice, '
                . 'and is never the result of selfishness." '
                . '(Napoleon Hill)',
            array('name' => 'Tahoma', 'size' => 10)
        );

        // Adding Text element with font customized using named font style...
        $fontStyleName = 'oneUserDefinedStyle';
        $phpWord->addFontStyle(
            $fontStyleName,
            array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
        );
        $section->addText(
            '"The greatest accomplishment is not in never falling, '
                . 'but in rising again after you fall." '
                . '(Vince Lombardi)',
            $fontStyleName
        );

        // Adding Text element with font customized using explicitly created font style object...
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Tahoma');
        $fontStyle->setSize(13);
        $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
        $myTextElement->setFontStyle($fontStyle);

        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('helloWorld.docx');

        // Saving the document as ODF file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
        $objWriter->save('helloWorld.odt');

        // Saving the document as HTML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        $objWriter->save('helloWorld.html');
    }
}
