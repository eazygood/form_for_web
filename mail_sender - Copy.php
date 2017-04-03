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


// Check, if data is not empty
if (empty($name)) {
  die('Отсутствует имя.');
} else if (empty($lastName)) {
  die('Отсутствует фамилия.');
} else if (empty($birthDay)) {
  die('Отсутствует год рождения или isikukood.');
} else if (empty($adress)) {
  die('Отсутствует адрес.');
} else if (empty($phone)) {
  die('Отсутствует номер телефона');
} else if (empty($userEmail)) {
  die("<script>$(\"#openModal\").show(1, function() {
    $('#result').html(\"<p style=color:red;>Отсутствует почта<br>E-post puudub<p>\")});</script>"); // Modal Window show that EMAIL is incorrect
} else if (empty($nameLastname)) {
  die('Отсутствует имя и фамилия в период работы');
} else if (empty($nameChanges)) {
  die('Отсутствует изменение фамилии, девичью фамилия');
} else if (empty($compName)) {
  die('Отсутствует наименование предприятия,отдела/цеха');
} else if (empty($workTime)) {
  die('Отсутствует период работы');
} else if (empty($compName)) {
  die('Отсутствует должность / специальность');
}

$the_file = '';
// If user choose file
if (!empty($_FILES['uploadfile']['tmp_name'])) {
  // Upload file
  $path = $_FILES['uploadfile']['name'];
  if (move_uploaded_file($_FILES['uploadfile']['tmp_name'], $path)) {
    $the_file = $path;
  }
}

// If uploaded file, then headers a little different
$headers = null;

if (empty($the_file)) {
  // this part of code are responsible for message without uploaded file
  // gathering headers
  $headers = array();
  $headers[] = "MIME-Version: 1.0";
  $headers[] = "Content-Type: text/html; charset=UTF-8";
  $headers[] = "From: $name <$userEmail>";
  $headers[] = "Subject: ";
  $headers[] = "X-Mailer: PHP/" . phpversion();
  // write a text
  $allmsg = "<p><b>E-Mail:</b> $userEmail</p>
            <ul>
                <li>$name</li>
                <li>$lastName</li> 
                <li>$birthDay </li>
                <li>$adress</li>
                <li>$phone</li>
                <li>$userEmail </li>
                <li>$radio</li>
                <li>$nameLastname</li>
                <li>$nameChanges</li>
                <li>$compName</li>
                <li>$workTime</li>
                <li>$speciality</li>
                </ul>";
  $allmsg = "<html><head><title>TEST TEST TEST</title><META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UTF-8\"></head><body>" . $allmsg . "</body></html>";
  // send
  if (!mail($to, 'Subject name goes here', $allmsg, implode("\r\n", $headers))) {
    echo 'Пиьсмо не отправлено - что то не сработало';
  } else {
    echo 'Отправлено письмо без вложения.';
  }

} else {
  // this part of code is responsible for sending an email with attachment
  $fp = fopen($the_file, "r");
  if (!$the_file) {
    die("Ошибка отправка письма: Файл $the_file не может быть прочитан.");
  } 
  $file = fread($fp, filesize($path));
  fclose($fp);
  // delete temporary file
  unlink($path);
  // gathering letter text
  $allmsg = "<p><b>E-Mail:</b> $userEmail</p>
            <ul>
                <li>$name</li>
                <li>$lastName</li> 
                <li>$birthDay </li>
                <li>$adress</li>
                <li>$phone</li>
                <li>$userEmail </li>
                <li>$radio</li>
                <li>$nameLastname</li>
                <li>$nameChanges</li>
                <li>$compName</li>
                <li>$workTime</li>
                <li>$speciality</li>
                </ul>";
  $allmsg = "<html><head><title>TEST TEST TEST</title><META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UTF-8\"></head><body>" . $allmsg . "</body></html>";
  // generate devider
  $boundary = "--" . md5(uniqid(time()));
  // gathering headers
  $headers = array();
  $headers[] = "MIME-Version: 1.0";
  $headers[] = "From: $name <$userEmail>";
  $headers[] = "Subject: ";
  $headers[] = "X-Mailer: PHP/" . phpversion();
  $headers[] = "Content-Type: multipart/mixed; boundary=\"$boundary\"\n";

  // gathering letter text +  attachment
  $multipart = array();
  $multipart[] = "--$boundary";
  $multipart[] = "Content-Type: text/html; charset=UTF-8";
  $multipart[] = "Content-Transfer-Encoding: Quot-Printed\r\n";
  $multipart[] = "$allmsg\r\n";
  $multipart[] = "--$boundary";
  $multipart[] = "Content-Type: application/octet-stream";
  $multipart[] = "Content-Transfer-Encoding: base64";
  $multipart[] = "Content-Disposition: attachment; filename = \"" . $path . "\"\r\n";
  $multipart[] = chunk_split(base64_encode($file));
  $multipart[] = "--$boundary";

  // send
  if (!mail($to, "subject", implode("\r\n", $multipart), implode("\r\n", $headers))) {
        echo 'Письмо не отправлено - что-то не сработало.';
    } else {
        echo 'Отправлено письмо с вложением.';
    }
}
