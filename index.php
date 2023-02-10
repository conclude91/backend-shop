<?php

if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'delete') {
    callAPI('DELETE', 'http://localhost/shop/api/product/delete', '{"id":"' . $_GET['id'] . '"}');
}

if (isset($_GET['action']) &&  $_GET['action'] == 'add') {
    callAPI('POST', 'http://localhost/shop/api/product/add', '{"id":"' . $_POST['id'] . '","image":"' . $_POST['image'] . '","name":"' . $_POST['name'] . '","description":"' . $_POST['description'] . '"}');
}

if (isset($_GET['action']) &&  $_GET['action'] == 'update') {
    callAPI('PUT', 'http://localhost/shop/api/product/update', '{"id":"' . $_POST['id'] . '","image":"' . $_POST['image'] . '","name":"' . $_POST['name'] . '","description":"' . $_POST['description'] . '"}');
}

function callAPI($method, $url, $data)
{
    $curl = curl_init();
    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            header('location: http://localhost/web_shop', true, 307);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            header('location: http://localhost/web_shop', true, 307);
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            header('location: http://localhost/web_shop', true, 307);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }
    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    // EXECUTE:
    $result = curl_exec($curl);
    if (!$result) {
        die("Connection Failure");
    }
    curl_close($curl);
    return $result;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP - CRUD (API + Bootstrap)</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
</head>

<body>
    <section>
        <h1 style="text-align: center;margin: 50px 0;">PHP CRUD (API + Bootstrap)</h1>
        <div class="container">
            <form action="?action=<?php echo (isset($_GET['id']) && isset($_GET['image']) && isset($_GET['name']) && isset($_GET['description'])) ? 'update' : 'add'; ?>" method="post">
                <div class="row">
                    <div class="form-group col-lg-4">
                        <label for="">ID</label>
                        <input type="text" name="id" id="id" class="form-control" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>" required>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="">Image</label>
                        <input type="text" name="image" id="image" class="form-control" value="<?php echo isset($_GET['image'])  ? $_GET['image'] : ''; ?>" required>
                    </div>
                    <div class="form-group col-lg-4">
                        <label for="">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($_GET['name'])  ? $_GET['name'] : ''; ?>" required>
                    </div>
                </div>
                <br>
                <div class="form-group col-lg-12">
                    <label for="">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5" required><?php echo isset($_GET['description'])  ? $_GET['description'] : ''; ?></textarea>
                </div>
                <br>
                <div class="row">
                    <div class="form-group col-lg-12" style="display: grid;align-items:  flex-end;">
                        <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Submit">
                    </div>
                </div>
            </form>
        </div>
    </section>
    <section style="margin: 50px 0;">
        <div class="container">
            <table class="table table-light">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Image</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $get_data = callAPI('GET', 'http://localhost/shop/api/product/fetch', false);
                    $response = json_decode($get_data, true);
                    $data = $response['data'];
                    for ($i = 0; $i < count($data); $i++) {
                        $id = $data[$i]['id'];
                        $image = $data[$i]['image'];
                        $name = $data[$i]['name'];
                        $description = $data[$i]['description'];
                    ?>
                        <tr class="trow">
                            <td><?php echo $id; ?></td>
                            <td>
                                <img src="<?php echo $image; ?>" width="500" height="600">
                            </td>
                            <td><?php echo $name; ?></td>
                            <td><?php echo $description; ?></td>
                            <td><a href="?id=<?php echo $data[$i]['id']; ?>&image=<?php echo $data[$i]['image']; ?>&name=<?php echo $data[$i]['name']; ?>&description=<?php echo $data[$i]['description']; ?>" class="btn btn-primary">Update</a></td>
                            <td><a href="?id=<?php echo $id; ?>&action=delete" class="btn btn-danger">Delete</a></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</body>

</html>