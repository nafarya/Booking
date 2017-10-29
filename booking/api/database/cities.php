<?php
function getCitiesByCountryId($connection, $id)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM cities WHERE country_id = ?")) {
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);

        mysqli_stmt_bind_result($statement, $cityId, $name, $countryId);
        $error = mysqli_stmt_error($statement);

        $cities = [];
        while (mysqli_stmt_fetch($statement)) {
            $cities[] = ['city_id' => $cityId, 'name' => $name];
        }

        mysqli_stmt_close($statement);

        $citiesCount = count($cities);
        return $error || !$citiesCount ? false : ['count' => $citiesCount, 'country_id' => $id, 'cities' => $cities];
    }
    return false;
}

function findCityById($connection, $id)
{
    $statement = mysqli_stmt_init($connection);
    if (mysqli_stmt_prepare($statement, "SELECT * FROM cities WHERE id = ?")) {
        mysqli_stmt_bind_param($statement, "i", $id);
        mysqli_stmt_execute($statement);

        mysqli_stmt_bind_result($statement, $cityId, $name, $countryId);
        mysqli_stmt_fetch($statement);

        $error = mysqli_stmt_error($statement);
        mysqli_stmt_close($statement);

        if ($cityId == $id) {
            return $error ? false : ['city_id' => $cityId, 'name' => $name, 'country_id' => $countryId];
        }
    }
    return false;
}

?>