<?php
date_default_timezone_set('Asia/Jakarta');
//include '../../function/db_connect.php';
class LoginForm extends DbConn
{
    public function checkLogin($myusername, $mypassword)
    {
        $conf = new GlobalConf;
        $ip_address = $conf->ip_address;
        $login_timeout = $conf->login_timeout;
        $max_attempts = $conf->max_attempts;
        $timeout_minutes = $conf->timeout_minutes;
        $attcheck = checkAttempts($myusername);
        $curr_attempts = $attcheck['attempts'];

        $datetimeNow = date("Y-m-d H:i:s");
        $oldTime = strtotime($attcheck['lastlogin']);
        $newTime = strtotime($datetimeNow);
        $timeDiff = $newTime - $oldTime;

        try {

            $db = new DbConn;
            $tbl_members = $db->tbl_members;
			$tbl_logs = $db->tbl_logs;
			$err = '';

        } catch (PDOException $e) {

            $err = "Error: " . $e->getMessage();

        }

        $stmt = $db->conn->prepare("SELECT * FROM ".$tbl_members." WHERE nis = :myusername");
        $stmt->bindParam(':myusername', $myusername);
        $stmt->execute();

        // Gets query result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($curr_attempts >= $max_attempts && $timeDiff < $login_timeout) {

            //Too many failed attempts
			$success = 'Kesempatan login sudah melebihi 5x...tunggu sekitar '.$timeout_minutes.' menit sebelum melakukan login ulang';
        
        } else {

             //If max attempts not exceeded, continue
            // Checks password entered against db password hash
          	$cektahun=substr($mypassword,0,4);
            $cekbulan=substr($mypassword,4,2);
            $cektanggal=substr($mypassword,6,2);
          	$cekpass=$cektahun.'-'.$cekbulan.'-'.$cektanggal;
            if ($result['tanggal']==$cekpass && $result['status'] == '1') {

                //Success! Register $myusername, $mypassword and return "true"
                $success = 'true';
                session_start();
                $_SESSION['peserta_didik_id'] = $result['peserta_didik_id'];
				
			
            } elseif ($result['tanggal']==$cekpass && $result['status'] != '1') {

                //Account not yet verified
				$success = 'Akun Anda sudah dinonaktifkan, untuk mengaktifkan kembali silahkan hubungi operator.';

            } else {

                //Wrong username or password
				$success = 'Username atau Password tidak ditemukan!';

            }
        }
        return $success;
    }

    public function insertAttempt($username)
    {
        try {
            $db = new DbConn;
            $conf = new GlobalConf;
            $tbl_attempts = $db->tbl_attempts;
            $ip_address = $conf->ip_address;
            $login_timeout = $conf->login_timeout;
            $max_attempts = $conf->max_attempts;

            $datetimeNow = date("Y-m-d H:i:s");
            $attcheck = checkAttempts($username);
            $curr_attempts = $attcheck['attempts'];

            $stmt = $db->conn->prepare("INSERT INTO ".$tbl_attempts." (ip, attempts, lastlogin, username) values(:ip, 1, :lastlogin, :username)");
            $stmt->bindParam(':ip', $ip_address);
            $stmt->bindParam(':lastlogin', $datetimeNow);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $curr_attempts++;
            $err = '';

        } catch (PDOException $e) {

            $err = "Error: " . $e->getMessage();

        }

        //Determines returned value ('true' or error code)
        $resp = ($err == '') ? 'true' : $err;

        return $resp;

    }

    public function updateAttempts($username)
    {
        try {
            $db = new DbConn;
            $conf = new GlobalConf;
            $tbl_attempts = $db->tbl_attempts;
            $ip_address = $conf->ip_address;
            $login_timeout = $conf->login_timeout;
            $max_attempts = $conf->max_attempts;
            $timeout_minutes = $conf->timeout_minutes;

            $att = new LoginForm;
            $attcheck = checkAttempts($username);
            $curr_attempts = $attcheck['attempts'];

            $datetimeNow = date("Y-m-d H:i:s");
            $oldTime = strtotime($attcheck['lastlogin']);
            $newTime = strtotime($datetimeNow);
            $timeDiff = $newTime - $oldTime;

            $err = '';
            $sql = '';

            if ($curr_attempts >= $max_attempts && $timeDiff < $login_timeout) {

                if ($timeDiff >= $login_timeout) {

                    $sql = "UPDATE ".$tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                    $curr_attempts = 1;

                }

            } else {

                if ($timeDiff < $login_timeout) {

                    $sql = "UPDATE ".$tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                    $curr_attempts++;

                } elseif ($timeDiff >= $login_timeout) {

                    $sql = "UPDATE ".$tbl_attempts." SET attempts = :attempts, lastlogin = :lastlogin where ip = :ip and username = :username";
                    $curr_attempts = 1;

                }

                $stmt2 = $db->conn->prepare($sql);
                $stmt2->bindParam(':attempts', $curr_attempts);
                $stmt2->bindParam(':ip', $ip_address);
                $stmt2->bindParam(':lastlogin', $datetimeNow);
                $stmt2->bindParam(':username', $username);
                $stmt2->execute();

            }

        } catch (PDOException $e) {

            $err = "Error: " . $e->getMessage();

        }

        //Determines returned value ('true' or error code) (ternary)
        $resp = ($err == '') ? 'true' : $err;

        return $resp;

    }

}
