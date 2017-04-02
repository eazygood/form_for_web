<?php

// EMAIL ADRESS
$to = 'itstryout@gmail.com';

// Receive data from users
// All data is required to check for correctness

$name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
$lastName = filter_input(INPUT_POST,'lastname', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
$birthDay = filter_input(INPUT_POST,'birthday', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
$adress = filter_input(INPUT_POST,'adress', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
$phone = filter_input(INPUT_POST,'phone', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
$userEmail = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$radio = filter_input(INPUT_POST, 'delivery', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
$nameLastname = filter_input(INPUT_POST,'name_lastname', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
$nameChanges = filter_input(INPUT_POST,'name_changes', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);
$compName = strip_tags(filter_input(INPUT_POST,'comp_name', FILTER_SANITIZE_MAGIC_QUOTES), '<p><a><b><div>');
$workTime = strip_tags(filter_input(INPUT_POST,'work_time', FILTER_SANITIZE_MAGIC_QUOTES), '<p><a><b><div>');
$speciality = strip_tags(filter_input(INPUT_POST,'speciality', FILTER_SANITIZE_MAGIC_QUOTES), '<p><a><b><div>');
$comments = strip_tags(filter_input(INPUT_POST,'comments', FILTER_SANITIZE_MAGIC_QUOTES), '<p><a><b><div>');
//$workingExperience = $_POST['working_experience'];
/*
  if (!empty($_POST['working_experience']) {
    $tmp = '';
    foreach($_POST['working_experience'] as $value) {
      $tmp .= $value . ',';
    }
  }
*/
// Check, if data is not empty
if (empty($name)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует имя<br>Nimi puudub<p>\")});</script>"); // Modal Window shows that missing Name
} else if (empty($lastName)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует фамилия<br>Perekonnanimi puudub<p>\")});</script>");
  // Modal Window shows that missing Lastname
} else if (empty($birthDay)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует год рождения или Личный номер<br>Sünnipäeva kuupäev või Isikukood puudub<p>\")});</script>"); // Modal Window shows that missing Birthday or  I.D number
} else if (empty($adress)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует адрес<br>Aadress puudub<p>\")});</script>"); // Modal Window shows that missing Adress
} else if (empty($phone)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует номер телефона<br>Tel.numbri puudub<p>\")});</script>"); // Modal Window shows that missing Phone
} else if (empty($userEmail)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует почта<br>E-post puudub<p>\")});</script>"); // Modal Window show that EMAIL is incorrect
} else if (empty($nameLastname)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует имя и фамилия в период работы<br>Ees- ja perekonnanimi töötamise ajal puudub<p>\")});</script>");
} else if (empty($nameChanges)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует изменение фамилии, девичью фамилия<br>Erinevad nimekujud ja nimemuutused,neiupõlvenimi puudub<p>\")});</script>");
} else if (empty($compName)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует наименование предприятия,отдела/цеха<br>Ettevõtte nimetus(ed), osakond/tsehh<p>\")});</script>");
} else if (empty($workTime)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует период работы<br>Töötamise aeg puudub<p>\")});</script>");
} else if (empty($compName)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует должность / специальность<br>Amet / eriala puudub<p>\")});</script>");
} /*else if (empty($workingExperience)) {
  // Check if the one of checkboxes checked or not
  die("<script>$(\"#openModal\").show(1, function() {
   $('#result').html(\"<p style=color:red;>Отсутствует выбор подтверждения трудового стажа<br>Puudub tööstazi valik tõestamiseks<p>\")});</script>");
}
*/


// If user choose file
if (isset($_FILES) && (bool) $_FILES) {
  // Upload file
  $AllowedExtensions = ["pdf","doc","docx","gif","jpeg","jpg","png","rtf","txt"];
        $files = [];
        $server_file = [];
        foreach($_FILES as $name => $file) {
            $file_name = $file["name"];
            $file_temp = $file["tmp_name"];
            foreach($file_name as $key) {
                $path_parts = pathinfo($key);
                $extension = strtolower($path_parts["extension"]);
                if(!in_array($extension, $AllowedExtensions)) { die("Extension not allowed"); }
                $server_file[] = "{$path_parts["basename"]}"; // put temporary folder name "uploads/ "
            }
            for($i = 0; $i<count($file_temp); $i++) { move_uploaded_file($file_temp[$i], $server_file[$i]); }
        }
}



// If uploaded file, then headers a little different
$headers = null;

if (empty($_FILES)) {
  // this part of code are responsible for message without uploaded file
  // gathering headers
  $headers = array();
  $headers[] = "MIME-Version: 1.0";
  $headers[] = "Content-Type: text/html; charset=UTF-8";
  $headers[] = "From: $name <$userEmail>";
  $headers[] = "Subject: ";
  $headers[] = "X-Mailer: PHP/" . phpversion();
  // write a text
  $allmsg = "<html>
  <body>
    <table>
      <thead><h3>Taotluse esitaja andmed</h3></thead>
      <tbody>
        <tr><td><strong>Nimi:</strong></td><td>$name</td></tr>
        <tr><td><strong>Perekonnanimi:</strong></td><td>$lastName</td></tr>
        <tr><td><strong>Sünnipäev / isikukood:</strong></td><td>$birthDay</td></tr>
        <tr><td><strong>Aadress:</strong></td><td>$adress</td></tr>
        <tr><td><strong>Tel:</strong></td><td>$phone</td></tr>
        <tr><td><strong>E-post:</strong></td><td>$userEmail</td></tr>
        <tr><td><strong>Kättesaamise viis:</strong></td><td>$radio</td></tr>
        </tbody>
    </table>
    <table>
      <thead><h3>Taotluse sisu</h3></thead>
      <tbody>
        <tr><td><strong>Ees- ja perekonnanimi töötamise ajal:</strong></td><td>$nameLastname</td></tr>
        <tr><td><strong>Erinevad nimekujud ja nimemuutused,neiupõlvenimi:</strong></td><td>$nameChanges</td></tr>
        <tr><td><strong>Ettevõtte nimetus(ed), osakond/tsehh:</strong></td><td>$compName</td></tr>
        <tr><td><strong>Töötamise aeg (võimalusel kuupäevaliselt):</strong></td><td>$workTime</td></tr>
        <tr><td><strong>Amet / eriala:</strong></td><td>$speciality</td></tr>
        <tr><td><strong>Täpsustused, kommentaarid:</strong></td><td>$comments</td></tr>
      </tbody>
    </table>
    <thead><h3>On vaja tõestada tööstaaz:</h3></thead>
  </body>
</html>";
  /*
  $message = '<html><body>';
  $message .= '<h1>Hello world</h1>';
  $message .= '</body></html';
  */
  // send
  if (!mail($to, 'Subject name goes here', $allmsg, implode("\r\n", $headers))) {
    echo 'Пиьсмо не отправлено - что то не сработало';
  } else {
    echo 'Отправлено письмо без вложения.';
  }

} else {
  // this part of code is responsible for sending an email with attachment
   
  /*
  $fp = fopen($the_file, "r");
  if (!$the_file) {
    die("Ошибка отправка письма: Файл $the_file не может быть прочитан.");
  } 

  $file = fread($fp, filesize($path));
  fclose($fp);
  // delete temporary file
  unlink($path);
  */
  // gathering letter text
  $allmsg = "<html>
  <body>
    <table>
      <thead><h3>Taotluse esitaja andmed</h3></thead>
      <tbody>
        <tr><td><strong>Nimi:</strong></td><td>$name</td></tr>
        <tr><td><strong>Perekonnanimi:</strong></td><td>$lastName</td></tr>
        <tr><td><strong>Sünnipäev / isikukood:</strong></td><td>$birthDay</td></tr>
        <tr><td><strong>Aadress:</strong></td><td>$adress</td></tr>
        <tr><td><strong>Tel:</strong></td><td>$phone</td></tr>
        <tr><td><strong>E-post:</strong></td><td>$userEmail</td></tr>
        <tr><td><strong>Kättesaamise viis:</strong></td><td>$radio</td></tr>
        </tbody>
    </table>
    <table>
      <thead><h3>Taotluse sisu</h3></thead>
      <tbody>
        <tr><td><strong>Ees- ja perekonnanimi töötamise ajal:</strong></td><td>$nameLastname</td></tr>
        <tr><td><strong>Erinevad nimekujud ja nimemuutused,neiupõlvenimi:</strong></td><td>$nameChanges</td></tr>
        <tr><td><strong>Ettevõtte nimetus(ed), osakond/tsehh:</strong></td><td>$compName</td></tr>
        <tr><td><strong>Töötamise aeg (võimalusel kuupäevaliselt):</strong></td><td>$workTime</td></tr>
        <tr><td><strong>Amet / eriala:</strong></td><td>$speciality</td></tr>
        <tr><td><strong>Täpsustused, kommentaarid:</strong></td><td>$comments</td></tr>
      </tbody>
    </table>
    <thead><h3>On vaja tõestada tööstaaz:</h3></thead>
  </body>
</html>";

  // generate devider
  
  //$boundary = "--" . md5(uniqid(time()));
  $semi_rand = md5(time());
  $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

  // gathering headers
  
  $headers = array();
  $headers[] = "MIME-Version: 1.0";
  $headers[] = "From: $name <$userEmail>";
  $headers[] = "Subject: ";
  $headers[] = "X-Mailer: PHP/" . phpversion();
  $headers[] = "Content-Type: multipart/mixed; boundary=\"{$mime_boundary}\"\n";

  // gathering letter text +  attachment
  //$message = "<html><body><h3>HEllo world</h3></body></html>";

  $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . "Content-Transfer-Encoding: Quot-Printed\n\n" . $allmsg . "\n\n";
        $message .= "--{$mime_boundary}\n";
        $FfilenameCount = 0;
        for($i = 0; $i<count($server_file); $i++) {
            $afile = fopen($server_file[$i],"rb");
            $data = fread($afile,filesize($server_file[$i]));
            fclose($afile);
            $data = chunk_split(base64_encode($data));
            $name = $file_name[$i];
            $message .= 
                      "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$name\"\n" .
                      "Content-Disposition: attachment;\n" . " filename=\"$name\"\n" .
                      "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
            $message .= "--{$mime_boundary}\n";
        }
        
  // send
  if (mail($to, "subject", $message, implode("\r\n", $headers))) {
        echo 'Отправлено письмо с вложением.';
        foreach ($server_file as $file){
              unlink($file);
        }

  } else {
        echo 'Письмо не отправлено - что-то не сработало.';
  }
}

