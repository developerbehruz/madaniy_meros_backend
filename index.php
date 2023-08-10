<?php
    include "db.php";

    $data = ['ok' => false, 'code' => null, 'message' => null, 'result' => []];

    if (isset($_REQUEST['do'])) {
        if ($_REQUEST['do'] == 'adminLogin') {
            if (isset($_REQUEST['adminLogin']) && isset($_REQUEST['adminPass'])) {
                $adminLogin = rStr($_REQUEST['adminLogin']);
                $adminPass = rStr($_REQUEST['adminPass']);

                $slt = "SELECT * FROM admin WHERE login = '$adminLogin' AND password = '$adminPass'";
                $query = mysqli_query($conn, $slt);

                if (mysqli_num_rows($query) > 0) {
                    foreach($query as $key => $value) {
                        $data['result'][] = $value;
                    }

                    $data['ok'] = true;
                    $data['code'] = 200;
                    $data['message'] = "Admin is confirmed!";
                } else {
                    $data['code'] = 402;
                    $data['message'] = "Admin did not match!";
                }
            } else {
                $data['code'] = 400;
                $data['message'] = "Adminlogin and adminpassword are required";
            }
        } else if ($_REQUEST['do'] == 'checkAdmin') {
            if (isset($_REQUEST['admin_u_id'])) {
                $admin_u_id = $_REQUEST['admin_u_id'];

                $slt = "SELECT * FROM admin WHERE unique_id = '$admin_u_id'";
                $query = mysqli_query($conn, $slt);

                if (mysqli_num_rows($query) > 0) {
                    foreach($query as $key => $value) {
                        $data['result'][] = $value;
                    }
                    $data['ok'] = true;
                    $data['code'] = 200;
                    $data['message'] = "Admin is confirmed!";
                } else {
                    $data['code'] = 402;
                    $data['message'] = "Admin did not match!";
                }
            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique id is required";
            }
        } else if ($_REQUEST['do'] == 'updateAdminLogin') {
            if (isset($_REQUEST['admin_u_id']) && isset($_REQUEST['old_login']) && isset($_REQUEST['old_pass']) && isset($_REQUEST['new_login']) && isset($_REQUEST['new_pass'])) {
                $admin_u_id = rStr($_REQUEST['admin_u_id']);
                $old_login = rStr($_REQUEST['old_login']);
                $old_pass = rStr($_REQUEST['old_pass']);

                $slt = "SELECT * FROM admin WHERE login = '$old_login' AND password = '$old_pass' AND unique_id = '$admin_u_id'";
                $query = mysqli_query($conn, $slt);

                if (mysqli_num_rows($query) > 0) {
                    $new_login = rStr($_REQUEST['new_login']);
                    $new_pass = rStr($_REQUEST['new_pass']);

                    $upd = "UPDATE admin SET login = '$new_login', password = '$new_pass' WHERE id = 1";
                    $query = mysqli_query($conn, $upd);

                    if ($query){
                        $data['ok'] = true;
                        $data['code'] = 200;
                        $data['message'] = "Admin data is changed";
                    } else {
                        $data['code'] = 403;
                        $data['message'] = "Setinerval error";
                    }
                } else {
                    $data['code'] = 402;
                    $data['message'] = "Admin did not match!";
                }
            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique id, old_login, old_pass (new_login or new_pass) are required";
            }
        } else if ($_REQUEST['do'] == 'addAgency') {
            if (isset($_REQUEST['admin_u_id']) && isset($_REQUEST['title']) && isset($_REQUEST['desc']) && isset($_REQUEST['phone']) && isset($_REQUEST['mail']) && isset($_FILES['img'])) {
                $admin_u_id = rStr(trim($_REQUEST['admin_u_id']));
                $title = rStr(trim($_REQUEST['title']));
                $desc = rStr(trim($_REQUEST['desc']));
                $phone = rStr(trim($_REQUEST['phone']));
                $mail = rStr(trim($_REQUEST['mail']));

                $allowed = array('png','jpg','jpeg','gif','jff');
                $filename = $_FILES['img']['name'];

                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (in_array($ext, $allowed)) {
                    if (file_exists('./uploads/' . $filename)) {
                        $filename = time() . "_" . $filename;
                    }
                    move_uploaded_file($_FILES['img']['tmp_name'], './uploads/' . $filename);

                    $slt = "SELECT * FROM admin WHERE unique_id = '$admin_u_id'";
                    $query = mysqli_query($conn, $slt);

                    if (mysqli_num_rows($query) > 0) {
                        $date = date("Y.m.d");
                        $ins = "INSERT INTO agency (title, description, phone, mail, date, img) VALUES ('$title', '$desc', '$phone', '$mail', '$date', '$filename')";
                        $query = mysqli_query($conn, $ins);

                        $data['ok'] = true;
                        $data['code'] = 200;
                        $data['message'] = "Agensy add successfully!";
                    } else {
                        $data['code'] = 402;
                        $data['message'] = "Admin did not match!";
                    }
                }else{
                    $data['code'] = 400;
                    $data['message'] = "File type is not supported";
                }
            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique_id, title, description, phone, mail and img are required";
            }
        } else if ($_REQUEST['do'] == 'getAllAgensy') {
            if (isset($_REQUEST['admin_u_id'])) {
                $admin_u_id = $_REQUEST['admin_u_id'];

                $slt = "SELECT * FROM admin WHERE unique_id = '$admin_u_id'";
                $query = mysqli_query($conn, $slt);

                if (mysqli_num_rows($query) > 0) {
                    $slt = "SELECT * FROM agency ORDER BY id DESC";
                    $query = mysqli_query($conn, $slt);

                    foreach($query as $key => $value) {
                        $data['result'][] = $value;
                    }
                    $data['ok'] = true;
                    $data['code'] = 200;
                    $data['message'] = "Admin is confirmed!";
                } else {
                    $data['code'] = 402;
                    $data['message'] = "Admin did not match!";
                }

            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique id is required";
            }
        } else if ($_REQUEST['do'] == 'deleteAgensy') {
            if (isset($_REQUEST['admin_u_id']) && isset($_REQUEST['agensy_id'])) {
                $admin_u_id = $_REQUEST['admin_u_id'];
                $agensy_id = $_REQUEST['agensy_id'];

                $slt = "SELECT * FROM admin WHERE unique_id = '$admin_u_id'";
                $query = mysqli_query($conn, $slt);

                if (mysqli_num_rows($query) > 0) {
                    $slt = "SELECT * FROM agency WHERE id = '$agensy_id'";
                    $query = mysqli_query($conn, $slt);
                    
                    if (mysqli_num_rows($query) > 0) {
                        $del = "DELETE FROM agency WHERE id = '$agensy_id'";
                        $query = mysqli_query($conn, $del);

                        if ($query) {
                            $data['ok'] = true;
                            $data['code'] = 200;
                            $data['message'] = "Delete agency successfully!";
                            $data['result'][] = $query;
                        } else {
                            $data['code'] = 403;
                            $data['message'] = "Setinerval error";
                        }
                    } else {
                        $data['code'] = 404;
                        $data['message'] = "Undefined agency_id";
                    }
                } else {
                    $data['code'] = 402;
                    $data['message'] = "Admin did not match!";
                }
            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique id and agensy_id are required";
            }
        } else if ($_REQUEST['do'] == 'addNews') {
            if (isset($_REQUEST['admin_u_id']) && isset($_REQUEST['title']) && isset($_REQUEST['desc']) && isset($_FILES['img'])) {
                $admin_u_id = rStr(trim($_REQUEST['admin_u_id']));
                $title = rStr(trim($_REQUEST['title']));
                $desc = rStr(trim($_REQUEST['desc']));

                $allowed = array('png','jpg','jpeg','gif','jff');
                $filename = $_FILES['img']['name'];

                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (in_array($ext, $allowed)) {
                    if (file_exists('./uploads/' . $filename)) {
                        $filename = time() . "_" . $filename;
                    }
                    move_uploaded_file($_FILES['img']['tmp_name'], './uploads/' . $filename);

                    $slt = "SELECT * FROM admin WHERE unique_id = '$admin_u_id'";
                    $query = mysqli_query($conn, $slt);

                    if (mysqli_num_rows($query) > 0) {
                        $date = date("Y.m.d");
                        $ins = "INSERT INTO news (title, description, date, img) VALUES ('$title', '$desc', '$date', '$filename')";
                        $query = mysqli_query($conn, $ins);

                        $data['ok'] = true;
                        $data['code'] = 200;
                        $data['message'] = "News add successfully!";
                    } else {
                        $data['code'] = 402;
                        $data['message'] = "Admin did not match!";
                    }
                }else{
                    $data['code'] = 400;
                    $data['message'] = "File type is not supported";
                }
            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique_id, title, description, and img are required";
            }
        } else if ($_REQUEST['do'] == 'getAllNews') {
            if (isset($_REQUEST['admin_u_id'])) {
                $admin_u_id = $_REQUEST['admin_u_id'];

                $slt = "SELECT * FROM admin WHERE unique_id = '$admin_u_id'";
                $query = mysqli_query($conn, $slt);

                if (mysqli_num_rows($query) > 0) {
                    $slt = "SELECT * FROM news ORDER BY id DESC";
                    $query = mysqli_query($conn, $slt);

                    foreach($query as $key => $value) {
                        $data['result'][] = $value;
                    }
                    $data['ok'] = true;
                    $data['code'] = 200;
                    $data['message'] = "Admin is confirmed!";
                } else {
                    $data['code'] = 402;
                    $data['message'] = "Admin did not match!";
                }

            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique id is required";
            }
        } else if ($_REQUEST['do'] == 'deleteNews') {
            if (isset($_REQUEST['admin_u_id']) && isset($_REQUEST['news_id'])) {
                $admin_u_id = $_REQUEST['admin_u_id'];
                $news_id = $_REQUEST['news_id'];

                $slt = "SELECT * FROM admin WHERE unique_id = '$admin_u_id'";
                $query = mysqli_query($conn, $slt);

                if (mysqli_num_rows($query) > 0) {
                    $slt = "SELECT * FROM news WHERE id = '$news_id'";
                    $query = mysqli_query($conn, $slt);
                    
                    if (mysqli_num_rows($query) > 0) {
                        $del = "DELETE FROM news WHERE id = '$news_id'";
                        $query = mysqli_query($conn, $del);

                        if ($query) {
                            $data['ok'] = true;
                            $data['code'] = 200;
                            $data['message'] = "Delete news successfully!";
                            $data['result'][] = $query;
                        } else {
                            $data['code'] = 403;
                            $data['message'] = "Setinerval error";
                        }
                    } else {
                        $data['code'] = 404;
                        $data['message'] = "Undefined news_id";
                    }
                } else {
                    $data['code'] = 402;
                    $data['message'] = "Admin did not match!";
                }
            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique id and news_id are required";
            }
        } else if ($_REQUEST['do'] == 'addOpenData') {
            if (isset($_REQUEST['admin_u_id']) && isset($_REQUEST['title']) && isset($_REQUEST['desc']) && isset($_FILES['file'])) {
                $admin_u_id = rStr(trim($_REQUEST['admin_u_id']));
                $title = rStr(trim($_REQUEST['title']));
                $desc = rStr(trim($_REQUEST['desc']));

                $allowed = array('png','jpg','jpeg','gif','jff','txt','docx','xls','xlsx','pdf');
                $filename = $_FILES['file']['name'];

                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if (in_array($ext, $allowed)) {
                    if (file_exists('./uploads/' . $filename)) {
                        $filename = time() . "_" . $filename;
                    }
                    move_uploaded_file($_FILES['file']['tmp_name'], './uploads/' . $filename);

                    $slt = "SELECT * FROM admin WHERE unique_id = '$admin_u_id'";
                    $query = mysqli_query($conn, $slt);

                    if (mysqli_num_rows($query) > 0) {
                        $date = date("Y.m.d");
                        $ins = "INSERT INTO openData (title, description, date, file) VALUES ('$title', '$desc', '$date', '$filename')";
                        $query = mysqli_query($conn, $ins);

                        $data['ok'] = true;
                        $data['code'] = 200;
                        $data['message'] = "Open data add successfully!";
                    } else {
                        $data['code'] = 402;
                        $data['message'] = "Admin did not match!";
                    }
                }else{
                    $data['code'] = 400;
                    $data['message'] = "File type is not supported";
                }
            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique_id, title, description, and file are required";
            }
        } else if ($_REQUEST['do'] == 'getAllOpenData') {
            if (isset($_REQUEST['admin_u_id'])) {
                $admin_u_id = $_REQUEST['admin_u_id'];

                $slt = "SELECT * FROM admin WHERE unique_id = '$admin_u_id'";
                $query = mysqli_query($conn, $slt);

                if (mysqli_num_rows($query) > 0) {
                    $slt = "SELECT * FROM openData ORDER BY id DESC";
                    $query = mysqli_query($conn, $slt);

                    foreach($query as $key => $value) {
                        $data['result'][] = $value;
                    }
                    $data['ok'] = true;
                    $data['code'] = 200;
                    $data['message'] = "Admin is confirmed!";
                } else {
                    $data['code'] = 402;
                    $data['message'] = "Admin did not match!";
                }

            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique id is required";
            }
        } else if ($_REQUEST['do'] == 'deleteOpenData') {
            if (isset($_REQUEST['admin_u_id']) && isset($_REQUEST['openData_id'])) {
                $admin_u_id = $_REQUEST['admin_u_id'];
                $openData_id = $_REQUEST['openData_id'];

                $slt = "SELECT * FROM admin WHERE unique_id = '$admin_u_id'";
                $query = mysqli_query($conn, $slt);

                if (mysqli_num_rows($query) > 0) {
                    $slt = "SELECT * FROM openData WHERE id = '$openData_id'";
                    $query = mysqli_query($conn, $slt);
                    
                    if (mysqli_num_rows($query) > 0) {
                        $del = "DELETE FROM openData WHERE id = '$openData_id'";
                        $query = mysqli_query($conn, $del);

                        if ($query) {
                            $data['ok'] = true;
                            $data['code'] = 200;
                            $data['message'] = "Delete openData successfully!";
                            $data['result'][] = $query;
                        } else {
                            $data['code'] = 403;
                            $data['message'] = "Setinerval error";
                        }
                    } else {
                        $data['code'] = 404;
                        $data['message'] = "Undefined openData_id";
                    }
                } else {
                    $data['code'] = 402;
                    $data['message'] = "Admin did not match!";
                }
            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique id and openData_id are required";
            }
        } else if ($_REQUEST['do'] == 'secretary') {
            if(isset($_REQUEST['admin_u_id']) && isset($_REQUEST['name']) && isset($_REQUEST['desc']) && isset($_REQUEST['phone']) && isset($_REQUEST['mail'])) {
                $name = rStr(trim($_REQUEST['name'])); 
                $desc = rStr(trim($_REQUEST['desc'])); 
                $phone = rStr(trim($_REQUEST['phone'])); 
                $mail = rStr(trim($_REQUEST['mail'])); 
                
                $slt = "SELECT * FROM secretary WHERE id = 1";
                $query = mysqli_query($conn, $slt) or die(mysqli_error($conn));
    
                if (mysqli_num_rows($query) > 0) {
                    $upd = "UPDATE secretary SET name = '$name', description = '$desc', phone = '$phone', mail = '$mail' WHERE id = 1";
                    $query = mysqli_query($conn, $upd);
    
                    if ($query) {
                        $data['ok'] = true;
                        $data['code'] = 200;
                        $data['message'] = "Secretary is changed successfully!";
                    } else {
                        $data['code'] = 403;
                        $data['message'] = "Setinerval error";
                    }
                } else {
                    $ins = "INSERT INTO secretary (name, description, phone, mail) VALUES ('$name', '$desc', '$phone', '$mail')";
                    $query = mysqli_query($conn, $ins) or die(mysqli_error($conn));
    
                    if ($query) {
                        $data['ok'] = true;
                        $data['code'] = 200;
                        $data['message'] = "Secretary is added successfully!";
                    } else {
                        $data['code'] = 403;
                        $data['message'] = "Setinerval error";
                    }
                }
            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique id, name, description, phone, mail are required";
            }
        } else if ($_REQUEST['do'] == 'getSecretary') {
            if (isset($_REQUEST['admin_u_id'])) {
                $admin_u_id = $_REQUEST['admin_u_id'];

                $slt = "SELECT * FROM admin WHERE unique_id = '$admin_u_id'";
                $query = mysqli_query($conn, $slt);

                if (mysqli_num_rows($query) > 0) {
                    $slt = "SELECT * FROM secretary ORDER BY id DESC";
                    $query = mysqli_query($conn, $slt);

                    foreach($query as $key => $value) {
                        $data['result'][] = $value;
                    }
                    $data['ok'] = true;
                    $data['code'] = 200;
                    $data['message'] = "Admin is confirmed!";
                } else {
                    $data['code'] = 402;
                    $data['message'] = "Admin did not match!";
                }

            } else {
                $data['code'] = 400;
                $data['message'] = "Admin unique id is required";
            }
        }
    } else {
        $data['code'] = 404;
        $data['message'] = "Method not found";
    }

    echo json_encode($data, JSON_PRETTY_PRINT);
?>