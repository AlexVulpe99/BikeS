<?php 

$CONFIG = [
    'servername' => "localhost",
    'username' => "root",
    'password' => '',
    'db' => 'bikes'
];
/** Regular statement */
$conn = new mysqli($CONFIG["servername"], $CONFIG["username"], $CONFIG["password"], $CONFIG["db"]);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function get_shop_items()
{
    GLOBAL $conn;

    $sql = "SELECT * FROM shop";
    $result = mysqli_query($conn, $sql);

    $array = array();

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($array, $row);
        }
    }

    return $array;
}

function get_all_news()
{
    GLOBAL $conn;

    $sql = "SELECT * FROM news";
    $result = mysqli_query($conn, $sql);

    $array = array();

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($array, $row);
        }
    }

    return $array;
}

function get_all_calendar()
{
    GLOBAL $conn;

    $sql = "SELECT * FROM calendar";
    $result = mysqli_query($conn, $sql);

    $array = array();

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($array, $row);
        }
    }

    return $array;
}

function get_all_appointments()
{
    GLOBAL $conn;

    $sql = "SELECT * FROM appointments WHERE response IS NULL";
    $result = mysqli_query($conn, $sql);

    $array = array();

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($array, $row);
        }
    }

    return $array;
}

function get_admin_appointments()
{
    GLOBAL $conn;

    $sql = "SELECT * FROM appointments WHERE response=1";
    $result = mysqli_query($conn, $sql);

    $array = array();

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($array, $row);
        }
    }

    return $array;
}

function get_bikes_by_uid($userid)
{
    GLOBAL $conn;

    $sql = "SELECT * FROM bikes WHERE userid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    $array = array();

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($array, $row);
        }
    }

    return $array;
}

function login_user($email)
{
    GLOBAL $conn;

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = mysqli_fetch_assoc($result);

    return $row;

}

function register_user($data)
{
    GLOBAL $conn;

    $sql = "SELECT * FROM users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $data->email);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) == 0)
    {
        $sql2 = "INSERT INTO `users` VALUES (?, ?, ?, NULL)";
        $stmt2 = $conn->prepare($sql2);
        $hash = password_hash($data->password,PASSWORD_BCRYPT);
        $stmt2->bind_param('sss', $data->uid,$data->email,$hash);
        $stmt2->execute();
        $status = 1;
    }
    else
    {
        $status = 0;
    }

    return $status;
}

function put_new_bike($userid, $reqData)
{
    GLOBAL $conn;

    $sql2 = "INSERT INTO `bikes` VALUES (?, ?, ?, ?, ?, ?, ?, NULL)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('sssisii', $reqData->bid,$userid,$reqData->material,$reqData->an,$reqData->stare,$reqData->greutate,$reqData->viteze);
    if($stmt2->execute())
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }

    return $status;

}

function put_new_appointment($userid, $reqData)
{
    GLOBAL $conn;

    $sql2 = "INSERT INTO `appointments` VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL, NULL)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('ssssssssss', $reqData->apid,$userid,$reqData->firstname,$reqData->lastname,$reqData->phone,$reqData->email,$reqData->brand,$reqData->seria,$reqData->problema,$reqData->data_inreg);
    if($stmt2->execute())
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }

    return $status;

}

function put_admin_appointment($userid, $reqData)
{
    GLOBAL $conn;

    $sql2 = "INSERT INTO `appointments` VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('ssssssssssis', $reqData->apid,$userid,$reqData->firstname,$reqData->lastname,$reqData->phone,$reqData->email,$reqData->brand,$reqData->seria,$reqData->problema,$reqData->data_inreg,$reqData->response,$reqData->data_end);
    if($stmt2->execute())
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }

    return $status;

}

function put_new_shop_item($reqData)
{
    GLOBAL $conn;

    $sql2 = "INSERT INTO `shop` VALUES (?, ?, NULL, ?, ?, ?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('ssdsiis', $reqData->iid,$reqData->category,$reqData->price,$reqData->country,$reqData->warranty,$reqData->series,$reqData->model);
    if($stmt2->execute())
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }

    return $status;

}

function put_new_news($reqData)
{
    GLOBAL $conn;

    $sql2 = "INSERT INTO `news` VALUES (?, NULL, ?, ?, CURRENT_TIMESTAMP)";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param('ssi', $reqData->nid,$reqData->descriere,$reqData->important);
    if($stmt2->execute())
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }

    return $status;

}

function update_appointment($reqData)
{
    GLOBAL $conn;

    $sql = "UPDATE `appointments` SET `response` = ? , `data_end` = ? WHERE `appointments`.`apid` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss',$reqData->response,$reqData->data_end,$reqData->apid);
    if($stmt->execute())
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }

    return $status;
    
}

function update_shop_item($reqData)
{
    GLOBAL $conn;

    $sql = "UPDATE `shop` SET `category` = ? , `price` = ? , `country` = ? , `warranty` = ? , `series` = ? , `model` = ? WHERE `shop`.`iid` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssdsiis',$reqData->category,$reqData->price,$reqData->country,$reqData->warranty,$reqData->series,$reqData->model,$reqData->iid);
    if($stmt->execute())
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }

    return $status;
    
}

function delete_bike($bikeid)
{
    GLOBAL $conn;

    $sql = "DELETE FROM `bikes` WHERE `bikes`.`bid` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s',$bikeid);
    if($stmt->execute())
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }

    return $status;
}

function delete_shop_item($itemid)
{
    GLOBAL $conn;

    $sql = "DELETE FROM `shop` WHERE `shop`.`iid` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s',$itemid);
    if($stmt->execute())
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }

    return $status;
}

function delete_news($newsid)
{
    GLOBAL $conn;

    $sql = "DELETE FROM `news` WHERE `news`.`nid` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s',$newsid);
    if($stmt->execute())
    {
        $status = 1;
    }
    else
    {
        $status = 0;
    }

    return $status;
}

?>