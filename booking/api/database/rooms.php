<?php
function addRoom($connection, $hotel_id, $title, $description, $price, $capacity)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "INSERT INTO rooms(hotel_id, title, description, price, capacity) VALUES(?, ?, ?, ?, ?)")) {
        mysqli_stmt_bind_param($statement, "issii", $hotel_id, htmlspecialchars($title), htmlspecialchars($description), $price, $capacity);
        mysqli_stmt_execute($statement);

        $error = mysqli_stmt_error($statement);
        $id = mysqli_stmt_insert_id($statement);

        mysqli_stmt_close($statement);

        return $error ? false : $id;
    }
    return false;
}

function findRoomById($connection, $id)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM rooms WHERE id = ?")) {
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);

        mysqli_stmt_bind_result($statement, $_id, $hotel_id, $title, $description, $price, $capacity);
        mysqli_stmt_fetch($statement);

        $error = mysqli_stmt_error($statement);

        mysqli_stmt_close($statement);

        if ($_id == $id) {
            return $error ? false : ['id' => $id, 'hotel_id' => $hotel_id, 'title' => $title, 'description' => $description,
                'price' => $price, 'capacity' => $capacity];
        }
    }
    return false;
}

function findRoomsForOwnerByHotelId($connection, $id, $page = 1)
{
    $items_per_page = 18;
    $offset = ($page - 1) * $items_per_page;

    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM rooms WHERE hotel_id = ? ORDER BY id DESC LIMIT ?,?")) {
        mysqli_stmt_bind_param($statement, "iii", $id, $offset, $items_per_page);
        mysqli_stmt_execute($statement);

        mysqli_stmt_bind_result($statement, $_id, $hotel_id, $title, $description, $price, $capacity);
        $error = mysqli_stmt_error($statement);

        $rooms = [];
        while (mysqli_stmt_fetch($statement)) {
            $rooms[] = ['id' => $_id, 'title' => $title, 'description' => $description,
                'price' => $price, 'capacity' => $capacity];
        }

        mysqli_stmt_close($statement);

        return $error ? false : ['count' => count($rooms), 'rooms' => $rooms];
    }
    return false;
}

?>