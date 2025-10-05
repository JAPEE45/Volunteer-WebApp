<?php
    include_once "db.php";

    $data = json_decode(file_get_contents("php://input"),true);
    if($data){
        $firstname = $data['firstName'];
        $middlename = $data['middleName'];
        $lastname = $data['lastName'];
        $birthplace = $data['birthPlace'];
        $sex = $data['sex'];
        $date_of_birth = $data['dob'];
        $religion = $data['religion'];
        $height = $data['height'];
        $weight = $data['weight'];
        $civilStatus = $data['civilStatus'];
        $spouse = $data['spouse'];
        $number_of_children = $data['children'];
        $mobile = $data['mobile'];
        $landline = $data['landline'];
        $address = $data['address'];
        $health = $data['health'];
        $medication = $data['medication'];
        $bloodType = $data['bloodType'];
        $elementary = $data['elementary'];
        $elemYearGrad = $data['elemYearGrad'];
        $highSchool = $data['highSchool'];
        $hsYearGrad = $data['hsYearGrad'];
        $college = $data['college'];
        $collegeYearGrad = $data['collegeYearGrad'];
        $postGrad = $data['postGrad'];
        $postGradYear = $data['postGradYear'];
        $skills = $data['skills'];
        $languages = $data['languages'];
        $involvements = $data['involvements'];
        $company = $data['company'];
        $position = $data['position'];
        $workDates = $data['workDates'];
        $redCrossMember = $data['redCrossMember'];
        $membershipType = $data['membershipType'];
        $trainings = $data['trainings'];
        $refName = $data['refName'];
        $refContact = $data['refContact'];
        $fullname = $data['fullName'];
        $age = $data['age'];
        $stmt = $conn->prepare("INSERT INTO `users`
        (`fullname`, `sex`, `date_of_birth`, `age`, `birthplace`, `religion`, `height`, `weight`, `civil_status`, `name_of_spouse`, `spouse_contact`, `number_of_children`, `mobile_number`, `land_line_number`, `full_address`, `street_block_plot`, `district_brgy_village`, `mun_city`, `province`, `zip_code`, `pmhcda`, `cmt`, `blood_type`, `ecp`, `ecp_relationship`, `ecp_landline`, `ecp_mobile_number`, `ecp_ottif`, `ecp_ottif_relationship`, `ecp_ottif_landline`, `ecp_ottif_mobile_number`, `f_name`, `f_age`, `f_occupation`, `m_name`, `m_occupation`, `m_age`, `number_of_siblings`, `ypitf`, `primary_school_name`, `primary_year_grad`, `primary_award`, `secondary_school_name`, `secondary_year_grad`, `secondary_award`, `tertiary_school_name`, `tertiary_course`, `tertiary_year_grad`, `tertiary_award`, `vocational_school_name`, `vocational_year_grad`, `vocational_award`, `higher_studies`, `higher_studies_year_grad`, `higher_award`, `talents`, `skills`, `languages`) VALUES 
        (?,?,?)");
        $stmt->bind_param("sssissddsss", $fullname, $sex, $date_of_birth, $age,$birthplace, $religion, $height, $weight, $civilStatus, $spouse, $mobile, $number_of_children, $mobile, $landline,$address,  )
    }else{
        echo json_encode(['error':'no data']);
    }

?>