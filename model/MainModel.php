<?php


class MainModel
{
    public static function getAmountStr(){
        global $DB;
        $result = $DB->query("
                    SELECT COUNT(idTasks) as AmountStrAll FROM `tasks` 
                  ");
        return current($result->fetch_all(MYSQLI_ASSOC))['AmountStrAll'];
    }

    public static  function getListTasksAll($startPoint,$indPoint,$sortingTask){
        global $DB;
        $query = "select 
                    tasks.idTasks as id,
                    users.name as name,
                    users.email as email,
                    tasks.textTasks as textTasks,
                    tasks.statusTasks as statusTasks
                  from users
                  join tasks on tasks.idUsers = users.idUsers
                  order by ".$sortingTask."
                  LIMIT ".$startPoint.",".$indPoint."";
        $result = $DB->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getIdUserByNameAndEmail($s_name,$s_email){
        global $DB;
        //check for existence name user
        $query = "select idUsers 
                    from users 
                    where lower(users.name)=lower('".$s_name."')
                    and lower(users.email)=lower('".$s_email."')";
        $requestUsers = $DB->query($query);
        $idUsers = current($requestUsers->fetch_all(MYSQLI_ASSOC))['idUsers'];
        //there isn't name user
        if(!isset($idUsers)){
            return $idUsers;
        }
        return $idUsers;
    }

    public static function getIdTasksByIdTasksAndIdUsers($s_name,$s_email,$s_textTasks){
        global $DB;
        $idUsers = self::getIdUserByNameAndEmail($s_name,$s_email);
        $IdTasks = null;
        if(isset($idUsers)){
            //check for existence name user
            $query = "select tasks.IdTasks 
                      from tasks 
                      join users on users.idUsers = tasks.idUsers
                      where trim(lower(tasks.textTasks))=trim(lower('".$s_textTasks."')) 
                        and tasks.idUsers='".$idUsers."'";
            $requestUsers = $DB->query($query);
            $IdTasks = current($requestUsers->fetch_all(MYSQLI_ASSOC))['IdTasks'];
        }
        //there isn't name user
        if(!isset($IdTasks) or !isset($idUsers) ){
            return false;
        }
        return true;
    }

    public static function insertUser($s_name,$s_email){
        global $DB;
        $idUsers = null;
        //check user
        $idUsers = self::getIdUserByNameAndEmail($s_name,$s_email);
        if(!isset($idUsers)) {
            $query = "insert into users (users.name,users.email) 
                      values ('".$s_name."','".$s_email."')  
                      ";
            $DB->query($query);
            //last add user
            $idUsers = self::getIdUserByNameAndEmail($s_name,$s_email);
        }
        return $idUsers;
    }

    public static function insertTask($s_name,$s_email,$s_textTasks,$s_statusTasks){
        global $DB;
        $result = true;
        $idUsers = self::insertUser($s_name,$s_email);
        $queryTask = "insert into tasks (tasks.idUsers, tasks.textTasks, tasks.statusTasks)
                      values ('".$idUsers."','".$s_textTasks."','".$s_statusTasks."') ";
        $requestTasks = $DB->query($queryTask);
        if (!$requestTasks){
            $result = $requestTasks;
        }
        return $result;
    }

    public static function editingTask($s_idTask,$s_textTasks,$s_statusTasks){
        global $DB;
        $result = true;
        $query = "update tasks
                  set tasks.textTasks = '".$s_textTasks."', tasks.statusTasks = '".$s_statusTasks."'
                  where tasks.idTasks = '".$s_idTask."'";
        $request = $DB->query($query);
        if (!$request){
            $result = $request;
        }
        return $result;
    }

    public static function checkedOnAccessRights($s_loginAdmin,$s_passwordAdmin){
        global $DB;
        $s_passwordAdmin = base64_encode($s_passwordAdmin);
        //check for existence name user
        $query = "select admin.idAdmin 
                    from admin 
                      where trim(lower(admin.login))=trim(lower('".$s_loginAdmin."')) 
                        and admin.password='".$s_passwordAdmin."'";
        $request = $DB->query($query);
        $IdAdmin = current($request->fetch_all(MYSQLI_ASSOC))['idAdmin'];
        if (isset($IdAdmin)){
            $_SESSION['time'] = time();
            $_SESSION['right_admin'] = '(Администратор)';
            return true;
        }
        if(!empty($_SESSION['right_admin'])){
            session_destroy();
        }
        return $IdAdmin;
    }


}