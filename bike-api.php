<?php

include_once './db_model.php';
// generate json web token
include_once 'php-jwt-master/src/BeforeValidException.php';
include_once 'php-jwt-master/src/ExpiredException.php';
include_once 'php-jwt-master/src/SignatureInvalidException.php';
include_once 'php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

$key = "BikeAccessTokenKey";
/* $dt = new DateTime(); // current time
 $dt->add(new DateInterval('PT1H'));
 $date = $dt->format('h:i:s');
 $exp = strtotime($date); */

function generateRandomString()
{
    $length = 20;
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$endpoint = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$requestHeaders = getallheaders();
$requestBodyAsString = file_get_contents('php://input');

// to set Response Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

function getBearerKey()
{
    $headers = getallheaders();
    // HEADER: Get the bearer key from the header
    if (!empty($headers)) { {
            return $headers['Bearer'];
        }
    }
    return null;
}



if ($method == 'GET') {
    if (preg_match('/^\/bike_api\/shopitems$/', $endpoint)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {

            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));

                $items = get_shop_items();

                echo json_encode(array(
                    "status" => "ok",
                    "message" => "Access granted.",
                    "data" => $items
                ));
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied.",
                    "error" => $e->getMessage()
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/news$/', $endpoint)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {

            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));

                $items = get_all_news();

                echo json_encode(array(
                    "status" => "ok",
                    "message" => "Access granted.",
                    "data" => $items
                ));
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/appointments$/', $endpoint)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {

            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));

                $items = get_all_appointments();

                echo json_encode(array(
                    "status" => "ok",
                    "message" => "Access granted.",
                    "data" => $items
                ));
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/adminapp$/', $endpoint)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {

            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));

                $items = get_admin_appointments();

                echo json_encode(array(
                    "status" => "ok",
                    "message" => "Access granted.",
                    "data" => $items
                ));
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/bikes$/', $endpoint, $matches)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {
            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $data = $decoded->data;

                $items = get_bikes_by_uid($data->id);

                echo json_encode(array(
                    "status" => "ok",
                    "message" => "Access granted.",
                    "data" => $items
                ));
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/calendar$/', $endpoint)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {

            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));

                $items = get_all_calendar();

                echo json_encode(array(
                    "status" => "ok",
                    "message" => "Access granted.",
                    "data" => $items
                ));
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    }
} else

if ($method == "POST") {
    if (preg_match('/^\/bike_api\/login$/', $endpoint)) {

        //LOGIN - request body - array email, parola

        $tempData = html_entity_decode($requestBodyAsString);
        $data = json_decode($tempData);

        $user = login_user($data->email);

        if (empty($user)) {
            // display message: unable to login
            echo json_encode(array(
                "message" => "Login failed. Email gresit!!"
            ));
        } else

        if (password_verify($data->password, $user['hash_password'])) {
            $dt = new DateTime(); // current time
            $dt->add(new DateInterval('PT1H'));
            $date = $dt->format('h:i:s');
            $exp = strtotime($date);
            //CREATE TOKEN
            $token = array(
                "data" => array(
                    "id" => $user['uid'],
                    "email" => $user['email'],
                )
            );

            // generate jwt
            $jwt = JWT::encode($token, $key);

            //check if is admin
            if (!empty($user['admin'])) {
                echo json_encode(
                    array(
                        "status" => "ok",
                        "message" => "Login successful.",
                        "admin" => true,
                        "jwt" => $jwt
                    )
                );
            } else {
                echo json_encode(
                    array(
                        "status" => "ok",
                        "message" => "Login successful.",
                        "jwt" => $jwt
                    )
                );
            }
        } else {

            // display message: unable to login
            echo json_encode(array(
                "message" => "Login failed. Parola incorecta!!"
            ));
        }
    } else

    if (preg_match('/^\/bike_api\/register$/', $endpoint)) {

        //register - request body - array email, parola

        $tempData = html_entity_decode($requestBodyAsString);
        $data = json_decode($tempData);

        if (!empty($data->email) && !empty($data->password)) {
            $data->uid = generateRandomString();
            $status = register_user($data);

            if ($status == 1) {
                $dt = new DateTime(); // current time
                $dt->add(new DateInterval('PT1H'));
                $date = $dt->format('h:i:s');
                $exp = strtotime($date);
                //CREATE TOKEN
                $token = array(
                    "data" => array(
                        "id" => $data->uid,
                        "email" => $data->email,
                    )
                );

                // generate jwt
                $jwt = JWT::encode($token, $key);
                echo json_encode(
                    array(
                        "status" => "ok",
                        "message" => "Register successful.",
                        "jwt" => $jwt
                    )
                );
            } else {

                // display message: unable to register
                echo json_encode(array("message" => "Register failed."));
            }
        } else {

            // display message: unable to register
            echo json_encode(array("message" => "Register failed."));
        }
    } else

    if (preg_match('/^\/bike_api\/appointments$/', $endpoint)) {

        //appointments - request body - array apid, response

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {
            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $jwtData = $decoded->data;


                //reqData contine : apid, response, data_end
                $tempData = html_entity_decode($requestBodyAsString);
                $reqData = json_decode($tempData);

                $status = update_appointment($reqData);

                if ($status == 1) {
                    echo json_encode(array(
                        "status" => "ok",
                        "message" => "Appointment updated."
                    ));
                } else {
                    echo json_encode(array(
                        "message" => "Access denied."
                    ));
                }
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/shopitems$/', $endpoint)) {

        //shopitems - request body - array iid, category,price,country,warranty,series,model

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {
            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $jwtData = $decoded->data;


                //reqData contine : iid, category,price,country,warranty,series,model
                $tempData = html_entity_decode($requestBodyAsString);
                $reqData = json_decode($tempData);

                $status = update_shop_item($reqData);

                if ($status == 1) {
                    echo json_encode(array(
                        "status" => "ok",
                        "message" => "Item updated."
                    ));
                } else {
                    echo json_encode(array(
                        "message" => "Access denied."
                    ));
                }
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    }
} else

if ($method == 'PUT') {
    if (preg_match('/^\/bike_api\/bikes$/', $endpoint, $matches)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {
            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $jwtData = $decoded->data;


                //reqData contine : material, an, stare, greutate, viteze
                $tempData = html_entity_decode($requestBodyAsString);
                $reqData = json_decode($tempData);
                $reqData->bid = generateRandomString();

                //data->id - user id-ul
                $status = put_new_bike($jwtData->id, $reqData);

                if ($status == 1) {
                    echo json_encode(array(
                        "status" => "ok",
                        "message" => "Bike created."
                    ));
                } else {
                    echo json_encode(array(
                        "message" => "Access denied."
                    ));
                }
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/appointments$/', $endpoint, $matches)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {
            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $jwtData = $decoded->data;


                //reqData contine : firstname, lastname, phone, email, brand, seria, problema, data_inreg, data_end
                $tempData = html_entity_decode($requestBodyAsString);
                $reqData = json_decode($tempData);
                $reqData->apid = generateRandomString();

                //data->id - user id-ul
                $status = put_new_appointment($jwtData->id, $reqData);

                if ($status == 1) {
                    echo json_encode(array(
                        "status" => "ok",
                        "message" => "Appointment created."
                    ));
                } else {
                    echo json_encode(array(
                        "message" => "Access denied."
                    ));
                }
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/calendar$/', $endpoint, $matches)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {
            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $jwtData = $decoded->data;


                //reqData contine : firstname, lastname, phone, email, brand, seria, problema, data_inreg, data_end
                $tempData = html_entity_decode($requestBodyAsString);
                $reqData = json_decode($tempData);
                $reqData->apid = generateRandomString();
                $reqData->firstname = '-';
                $reqData->lastname = '-';
                $reqData->phone = '-';
                $reqData->email = '-';
                $reqData->brand = '-';
                $reqData->seria = '-';
                $reqData->response = 1;


                //data->id - user id-ul
                $status = put_admin_appointment($jwtData->id, $reqData);

                if ($status == 1) {
                    echo json_encode(array(
                        "status" => "ok",
                        "message" => "Appointment created."
                    ));
                } else {
                    echo json_encode(array(
                        "message" => "Access denied."
                    ));
                }
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/shopitems$/', $endpoint, $matches)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {
            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $jwtData = $decoded->data;


                //reqData contine : category, price, country, warranty, series, model
                $tempData = html_entity_decode($requestBodyAsString);
                $reqData = json_decode($tempData);
                $reqData->iid = generateRandomString();

                //data->id - user id-ul
                $status = put_new_shop_item($reqData);

                if ($status == 1) {
                    echo json_encode(array(
                        "status" => "ok",
                        "message" => "Shop item created."
                    ));
                } else {
                    echo json_encode(array(
                        "message" => "Access denied."
                    ));
                }
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/news$/', $endpoint, $matches)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {
            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $jwtData = $decoded->data;


                //reqData contine : descriere, important
                $tempData = html_entity_decode($requestBodyAsString);
                $reqData = json_decode($tempData);
                $reqData->nid = generateRandomString();

                if ($reqData->important == 0)
                    $reqData->important = NULL;

                //data->id - user id-ul
                $status = put_new_news($reqData);

                if ($status == 1) {
                    echo json_encode(array(
                        "status" => "ok",
                        "message" => "News log created."
                    ));
                } else {
                    echo json_encode(array(
                        "message" => "Access denied."
                    ));
                }
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    }
} else

if ($method == 'DELETE') {
    if (preg_match('/^\/bike_api\/bikes$/', $endpoint, $matches)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {
            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $jwtData = $decoded->data;


                //reqData contine : bid
                $tempData = html_entity_decode($requestBodyAsString);
                $reqData = json_decode($tempData);

                //data->id - user id-ul
                $status = delete_bike($reqData->bid);

                if ($status == 1) {
                    echo json_encode(array(
                        "status" => "ok",
                        "message" => "Bike deleted."
                    ));
                } else {
                    echo json_encode(array(
                        "message" => "Access denied."
                    ));
                }
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/shopitems$/', $endpoint, $matches)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {
            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $jwtData = $decoded->data;


                //reqData contine : iid
                $tempData = html_entity_decode($requestBodyAsString);
                $reqData = json_decode($tempData);

                //data->id - user id-ul
                $status = delete_shop_item($reqData->iid);

                if ($status == 1) {
                    echo json_encode(array(
                        "status" => "ok",
                        "message" => "Shop item deleted."
                    ));
                } else {
                    echo json_encode(array(
                        "message" => "Access denied."
                    ));
                }
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    } else

    if (preg_match('/^\/bike_api\/news$/', $endpoint, $matches)) {

        $data = getBearerKey();

        if (isset($data)) {
            $jwt = $data;
        } else {
            echo json_encode(array(
                "message" => "Failed connection. Token missing"
            ));
        }

        if ($jwt) {
            try {
                // decode jwt // $decoded->data->id // $decoded->data->email
                $decoded = JWT::decode($jwt, $key, array('HS256'));
                $jwtData = $decoded->data;


                //reqData contine : nid
                $tempData = html_entity_decode($requestBodyAsString);
                $reqData = json_decode($tempData);

                //data->id - user id-ul
                $status = delete_news($reqData->nid);

                if ($status == 1) {
                    echo json_encode(array(
                        "status" => "ok",
                        "message" => "News log deleted."
                    ));
                } else {
                    echo json_encode(array(
                        "message" => "Access denied."
                    ));
                }
            } catch (Exception $e) {

                echo json_encode(array(
                    "message" => "Access denied."
                ));
            }
        }
    }
}
