<?php
namespace DB;
use mysqli_sql_exception;
class DBAccess{
    private const HOST_DB = "localhost";
    private const DATABASE_NAME = "michelonr";
    private const USERNAME = "root";
    private const PASSWORD = "";

    private $connection;

    public function openDBConnection(){
        try {
            $this -> connection = mysqli_connect(
                self::HOST_DB,
                self::USERNAME,
                self::PASSWORD,
                self::DATABASE_NAME
            );
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function closeConnection(){
        mysqli_close($this->connection);
    }

    public function errorDB(bool $case){
        $case ? header("location: ../php/500.php")
            : header("location: ../php/404.php");
        die();
    }

    // QUERIES

    public function getArtist($id){
        $query = "SELECT Users.id, Users.username, Users.name, Users.lastname, Users.image, Users.birth_date, Users.birth_place, Users.biography, Users.experience
                    FROM Users
                    WHERE Users.id = $id AND NOT Users.is_admin";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtistPreview($id){
        $query = "SELECT Users.id, Users.username, Users.name, Users.lastname, Users.image
                    FROM Users
                    WHERE Users.id = $id AND NOT Users.is_admin";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtistWithArtworks($id){
        $query = "SELECT Users.id, Users.username, Users.name, Users.lastname, Users.image, Users.birth_date, Users.birth_place, Users.biography, Users.experience, Artworks.*
                    FROM Users JOIN Artworks ON Users.id = Artworks.id_artist
                    WHERE Users.id = $id AND NOT Users.is_admin
                    ORDER BY Artworks.upload_time DESC";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtistWithArtworksPreview($id){
        $query = "SELECT Users.id, Users.username, Users.name, Users.lastname, Users.image, Users.birth_date, Users.birth_place, Users.biography, Users.experience, Artworks.id AS id_artwork, Artworks.main_image, Artworks.title
                    FROM Users JOIN Artworks ON Users.id = Artworks.id_artist
                    WHERE Users.id = $id AND NOT Users.is_admin
                    ORDER BY Artworks.upload_time DESC";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtistArtworksPreview($id){
        $query = "SELECT Artworks.id, Artworks.main_image, Artworks.title
                    FROM Users JOIN Artworks ON Users.id = Artworks.id_artist
                    WHERE Users.id = $id AND NOT Users.is_admin
                    ORDER BY Artworks.upload_time DESC";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtwork($id){
        $query = "SELECT *
                    FROM Artworks
                    WHERE Artworks.id = $id";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtshow($id){
        $query = "SELECT *
                    FROM Artshows
                    WHERE Artshows.id = $id";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtworkPreview($id){
        $query = "SELECT Artworks.id, Artworks.title, Artworks.main_image, Users.id, Users.username
                    FROM Artworks JOIN Users ON Artworks.id_artist = Users.id
                    WHERE Artworks.id = $id";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtworkImages($id){
        $query = "SELECT Artworks.main_image
                    FROM Artworks
                    WHERE Artworks.id = $id
                    UNION
                    SELECT ArtworkDetails.image
                    FROM Artworks JOIN ArtworkDetails ON Artworks.id = ArtworkDetails.id_artwork
                    WHERE Artworks.id = $id
                    ORDER BY ArtworkDetails.image DESC";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtworkAdditionalImages($id){
        $query = "SELECT ArtworkDetails.image
                    FROM Artworks JOIN ArtworkDetails ON Artworks.id = ArtworkDetails.id_artwork
                    WHERE Artworks.id = $id
                    ORDER BY ArtworkDetails.image DESC";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtworkWithArtist($id){
        $query = "SELECT Artworks.*, Users.id, Users.username, Users.name, Users.lastname, Users.image, Users.birth_date, Users.birth_place, Users.biography, Users.experience
                    FROM Artworks JOIN Users ON Artworks.id_artist = Users.id
                    WHERE Artworks.id = $id";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtworkLabels($id){
        $query = "SELECT DISTINCT ArtworkLabels.label
                    FROM Artworks JOIN ArtworkLabels ON Artworks.id = ArtworkLabels.id_artwork
                    WHERE Artworks.id = $id
                    ORDER BY ArtworkLabels.label";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtistLabels($id){
        $query = "SELECT DISTINCT ArtworkLabels.label
                    FROM Artworks JOIN ArtworkLabels ON Artworks.id = ArtworkLabels.id_artwork
                    WHERE Artworks.id_artist = $id
                    ORDER BY ArtworkLabels.label";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getSimilarArtworks($id){
        $query = "SELECT Artworks.id, Artworks.title, Artworks.main_image, Users.id AS artist_id, Users.username 
                    FROM (Artworks JOIN Users ON Artworks.id_artist = Users.id)
                    JOIN (
                        SELECT A.id
                        FROM Artworks AS A
                        JOIN ArtworkLabels AS AL1 ON A.id = AL1.id_artwork
                        JOIN ArtworkLabels AL2 ON AL1.label = AL2.label
                        WHERE AL2.id_artwork = $id AND A.id <> $id
                        GROUP BY A.id
                        HAVING COUNT(DISTINCT AL1.label) >= (SELECT LEAST(2, COUNT(DISTINCT ArtworkLabels.label)) FROM ArtworkLabels WHERE ArtworkLabels.id_artwork = $id)
                    ) AS QR ON Artworks.id = QR.id";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getLabels(){
        $query = "SELECT * FROM Labels";
        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtworksQuery($text = "", $time = "", $height = "", $width = "", $depth = "", $labels = array()){
        $time_filter = "";
        if($time != ""){
            $start_year = explode("-", $time)[0];
            $end_year = explode("-", $time)[1];
            $time_filter = "AND NOT
                (YEAR(Artworks.start_date) >= $end_year OR YEAR(Artworks.end_date) <= $start_year)";
        }
        $height_filter = "";
        if($height != ""){
            $start_height = explode("-", $height)[0];
            $end_height = explode("-", $height)[1];
            $height_filter = "AND Artworks.height >= $start_height AND Artworks.height <= $end_height";
        }
        $width_filter = "";
        if($width != ""){
            $start_width = explode("-", $width)[0];
            $end_width = explode("-", $width)[1];
            $width_filter = "AND Artworks.width >= $start_width AND Artworks.width <= $end_width";
        }
        $depth_filter = "";
        if($depth != ""){
            $start_depth = explode("-", $depth)[0];
            $end_depth = explode("-", $depth)[1];
            $depth_filter = "AND Artworks.length >= $start_depth AND Artworks.length <= $end_depth";
        }
        $labels_filter = "";
        $groupby_labels_filter = "";
        if(count($labels) > 0){
            $labels_filter = "AND ArtworkLabels.label IN ('" . implode("', '", $labels) . "')";
            $groupby_labels_filter = "GROUP BY Artworks.id HAVING COUNT(DISTINCT ArtworkLabels.label) >= " . count($labels);
        }

        $query = "SELECT DISTINCT A1.id AS artistID, A1.title, A1.main_image, U1.id AS artworkID, U1.username
                    FROM (Artworks AS A1 JOIN Users AS U1 ON A1.id_artist = U1.id)
                    JOIN (
                        SELECT DISTINCT Artworks.id
                        FROM (Artworks LEFT OUTER JOIN Users ON Artworks.id_artist = Users.id) LEFT OUTER JOIN ArtworkLabels ON Artworks.id = ArtworkLabels.id_artwork
                        WHERE (Artworks.title LIKE '%$text%'
                            $time_filter
                            $height_filter
                            $width_filter
                            $depth_filter
                            $labels_filter
                        ) $groupby_labels_filter ) AS QR ON A1.id = QR.id
                    ORDER BY A1.upload_time DESC";
        
        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtistQuery($text = "", $time = "", $isPresentInArtshow = False, $labels = array()){
        $time_filter = "";
        if($time != ""){
            $time_filter = "AND YEAR(Users.birth_date) = $time";
        }
        $labels_filter = "";
        if(count($labels) > 0){
            $labels_filter = "AND ArtworkLabels.label IN ('" . implode("', '", $labels) . "')
                            GROUP BY Users.id
                            HAVING COUNT(DISTINCT ArtworkLabels.label) >= " . count($labels);
        }
        $present_in_artshow_filter = "";
        if($isPresentInArtshow){
            $present_in_artshow_filter = "JOIN ArtshowPrenotations ON U1.id = ArtshowPrenotations.id_artist JOIN Artshows ON ArtshowPrenotations.id_artshow = Artshows.id
                                            WHERE CURRENT_DATE() >= Artshows.start_date AND CURRENT_DATE() <= Artshows.end_date
                                            ORDER BY U1.username";
        }

        $query = "SELECT DISTINCT U1.id, U1.username, U1.name, U1.lastname, U1.image
                    FROM Users AS U1
                    JOIN (
                        SELECT DISTINCT Users.id
                        FROM (Users LEFT OUTER JOIN Artworks ON Users.id = Artworks.id_artist) LEFT OUTER JOIN ArtworkLabels ON Artworks.id = ArtworkLabels.id_artwork
                        WHERE (Users.username LIKE '%$text%' OR Users.name LIKE '%$text%' OR Users.lastname LIKE '%$text%')
                            AND NOT Users.is_admin
                            $time_filter
                            $labels_filter
                        ) AS QR ON U1.id = QR.id
                    $present_in_artshow_filter";
                    
        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtshowsQuery($text = "", $start_date = "", $end_date = ""){
        $start_date_filter = "'$start_date'";
        if($start_date == ""){
            $start_date_filter = "CURRENT_DATE";
        }
        $end_date_filter = "";
        if($end_date != ""){
            $end_date_filter = "Artshows.start_date >= '$end_date' OR ";
        }
        $time_filter = "AND NOT (".$end_date_filter." Artshows.end_date <= $start_date_filter)";
        
        $query = "SELECT Artshows.*
                FROM Artshows
                WHERE Artshows.title LIKE '%$text%' 
                $time_filter
                ";
    
        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getNextArtshow(){
        $query = "SELECT id, title, image, start_date, end_date FROM artshows
        WHERE (start_date >= CURRENT_DATE OR end_date >= CURRENT_DATE)
        ORDER BY start_date LIMIT 1";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtshowsNextMonth(){
        $query = "SELECT Artshows.*
                FROM Artshows
                WHERE (Artshows.start_date BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL 30 DAY)
                    OR (Artshows.end_date BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL 30 DAY)";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getNextArtshowOfArtist($id){
        $query = "SELECT Artshows.*
                FROM Users JOIN ArtshowPrenotations ON Users.id = ArtshowPrenotations.id_artist JOIN Artshows ON ArtshowPrenotations.id_artshow = Artshows.id
                WHERE Users.id = $id AND CURRENT_DATE <= Artshows.start_date
                ORDER BY Artshows.start_date ASC
                LIMIT 1";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtshowsInPeriod($start_date="", $end_date=""){
        $start_date_filter = "'$start_date'";
        if($start_date == ""){
            $start_date_filter = "CURRENT_DATE";
        }
        $end_date_filter = "";
        if($end_date != ""){
            $end_date_filter = "Artshows.start_date >= '$end_date' OR ";
        }
        $time_filter = "NOT (".$end_date_filter." Artshows.end_date <= $start_date_filter)";

        $query = "SELECT Artshows.*
                    FROM Artshows
                    WHERE $time_filter";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getArtshowsPartecipants($id){
        $query = "SELECT Users.id, Users.username, Users.name, Users.lastname, Users.image
                    FROM ArtshowPrenotations JOIN Users ON ArtshowPrenotations.id_artist = Users.id
                    WHERE ArtshowPrenotations.id_artshow = $id";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function getLoggedUserPrenotationArtshow($id_artist, $id_artshow){
        $query = "SELECT Users.id, Users.username, Users.name, Users.lastname, Users.image, ArtshowPrenotations.time
                    FROM ArtshowPrenotations JOIN Users ON ArtshowPrenotations.id_artist = Users.id
                    WHERE ArtshowPrenotations.id_artshow = $id_artshow AND ArtshowPrenotations.id_artist = $id_artist";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function insertPrenotation($id_artist, $id_artshow){
        $query_insert = "INSERT INTO ArtshowPrenotations(id_artshow, id_artist, time)
                            VALUES ($id_artshow, $id_artist, CURRENT_TIMESTAMP())";

        mysqli_query($this->connection, $query_insert) or die(mysqli_error($this->connection));
        return mysqli_affected_rows($this->connection)>0;
    }

    public function deletePrenotation($id_artist, $id_artshow){
        $query_delete = "DELETE FROM ArtshowPrenotations
                            WHERE ArtshowPrenotations.id_artshow = $id_artshow
                            AND ArtshowPrenotations.id_artist = $id_artist";

        mysqli_query($this->connection, $query_delete) or die(mysqli_error($this->connection));
        return mysqli_affected_rows($this->connection)>0;
    }

    public function insertNewArtwork($title, $main_image, $description="", $height="", $width="", $length="", $start_date="", $end_date="", $id_artist, $additional_images = array(), $labels = array()){
        $this->connection->begin_transaction();
        try {
            $query_insert_artwork = "INSERT INTO Artworks(title, main_image, description, height, width, length, start_date, end_date, upload_time, id_artist)
                                        VALUES ('$title', '$main_image', NULLIF('$description', ''), NULLIF('$height', ''), NULLIF('$width', ''), NULLIF('$length', ''),
                                            NULLIF('$start_date', ''), NULLIF('$end_date', ''), CURRENT_TIMESTAMP(), $id_artist)";
            $this->connection->query($query_insert_artwork);

            $last_inserted_id = $this->connection->insert_id;

            foreach ($additional_images as $additional_image) {
                $query_insert_additional_image = "INSERT INTO ArtworkDetails(id_artwork, image) VALUES ($last_inserted_id, '$additional_image')";
                $this->connection->query($query_insert_additional_image);
            }

            foreach ($labels as $label) {
                $query_insert_label = "INSERT INTO ArtworkLabels(id_artwork, label) VALUES ($last_inserted_id, '$label')";
                $this->connection->query($query_insert_label);
            }

            $this->connection->commit();
            return $last_inserted_id;
        } catch (\Exception $e) {
            $this->connection->rollback();
            return False;
        }
    }

    public function insertNewArtshow($title, $description, $image, $start_date, $end_date){
        $query_insert = "INSERT INTO Artshows(title, description, image, start_date, end_date)
                            VALUES ('$title', '$description', NULLIF('$image', ''), '$start_date', '$end_date')";

        mysqli_query($this->connection, $query_insert) or die(mysqli_error($this->connection));
        return mysqli_affected_rows($this->connection)>0;
    }

    public function modifyUser($id, $username, $name, $lastname, $image, $birth_date, $birth_place, $biography, $experience){
        $mod_username = "username = '$username'";
        $mod_name = "name = '$name'";
        $mod_lastname = "lastname = '$lastname'";
        // su user c'è anche image perché può toglierla (delete imm da cartella users e update value a '')
        // mentre Artwork.main_image è obbligatoria e il value rimane quello, cambia solo l'imm dalla cartella artworks
        $mod_image = "image = NULLIF('$image', '')";
        $mod_birth_date = "birth_date = NULLIF('$birth_date', '')";
        $mod_birth_place = "birth_place = NULLIF('$birth_place', '')";
        $mod_biography = "biography = NULLIF('$biography', '')";
        $mod_experience = "experience = NULLIF('$experience', '')";
        
        $query_update_user = "UPDATE Users
                            SET $mod_username, 
                                $mod_name, 
                                $mod_lastname, 
                                $mod_image, 
                                $mod_birth_date, 
                                $mod_birth_place, 
                                $mod_biography, 
                                $mod_experience
                            WHERE Users.id = $id";

        mysqli_query($this->connection, $query_update_user) or die(mysqli_error($this->connection));
        return mysqli_affected_rows($this->connection)>0;
    }

    public function modifyArtwork($id, $title, $main_image, $description, $height, $width, $length, $start_date, $end_date, $additional_images, $labels){
        $mod_title="title = '$title',";
        $mod_description="description = '$description',";
        $mod_height="height = NULLIF('$height', ''),";
        $mod_width="width = NULLIF('$width', ''),";
        $mod_length="length = NULLIF('$length', ''),";
        $mod_start_date="start_date = NULLIF('$start_date', ''),";
        $mod_end_date="end_date = NULLIF('$end_date', '')";
        
        $main_image_not_empty = "";
        if(!empty($main_image)){
            $main_image_not_empty = "main_image = '$main_image' ,";
        }

        $this->connection->begin_transaction();
        try {
            $query_update_artwork = "UPDATE Artworks
                                        SET $mod_title 
                                            $main_image_not_empty 
                                            $mod_description 
                                            $mod_height 
                                            $mod_width 
                                            $mod_length 
                                            $mod_start_date 
                                            $mod_end_date
                                        WHERE Artworks.id = $id";
            $this->connection->query($query_update_artwork);
            
            if($additional_images !== null){
                $query_delete_additional_images = "DELETE FROM ArtworkDetails WHERE ArtworkDetails.id_artwork = $id";
                $this->connection->query($query_delete_additional_images);

                foreach ($additional_images as $additional_image) {
                    $query_insert_additional_images = "INSERT INTO ArtworkDetails(id_artwork, image) VALUES ($id, '$additional_image')";
                    $this->connection->query($query_insert_additional_images);
                }
            }

            $query_delete_additional_images = "DELETE FROM ArtworkLabels WHERE ArtworkLabels.id_artwork = $id";
            $this->connection->query($query_delete_additional_images);

            foreach ($labels as $label) {
                $query_insert_label = "INSERT INTO ArtworkLabels(id_artwork, label) VALUES ($id, '$label')";
                $this->connection->query($query_insert_label);
            }

            $this->connection->commit();
            return $id;
        } catch (\Exception $e) {
            $this->connection->rollback();
            return False;
        }
    }

    public function modifyArtshow($id, $title, $description, $image, $start_date, $end_date){
        $mod_title = "title = '$title'";
        $mod_description = "description = NULLIF('$description', '')";
        $mod_image = "image = NULLIF('$image', '')";
        $mod_start_date = "start_date = NULLIF('$start_date', '')";
        $mod_end_date = "end_date = NULLIF('$end_date', '')";
        
        $query_update_user = "UPDATE Artshows
                            SET $mod_title, 
                                $mod_description, 
                                $mod_image, 
                                $mod_start_date, 
                                $mod_end_date
                            WHERE Artshows.id = $id";

        mysqli_query($this->connection, $query_update_user) or die(mysqli_error($this->connection));
        return mysqli_affected_rows($this->connection)>0;
    }

    public function getUserLogin($username){
        $query = "SELECT Users.*
                    FROM Users
                    WHERE Users.username = '$username'";

        $queryResult = mysqli_query($this->connection, $query) or DBAccess::errorDB(true);/* die("Errore in DBAccess".mysqli_error($this->connection)); */
        if (mysqli_num_rows($queryResult) != 0){
            $result=array();
            while($row = mysqli_fetch_array($queryResult, MYSQLI_ASSOC)){
                $result[]=$row;
            }
            $queryResult->free();
            return $result;
        }
        else return null;
    }

    public function insertNewUser($username, $password, $name, $lastname, $image, $birth_date, $birth_place, $biography, $experience){
        $query_insert = "INSERT INTO Users(username, password, name, lastname, image, birth_date, birth_place, biography, experience)
                            VALUES ('$username', '$password', '$name', '$lastname', NULLIF('$image', ''), NULLIF('$birth_date', ''), NULLIF('$birth_place', ''), NULLIF('$biography', ''), NULLIF('$experience', ''))";

        try{mysqli_query($this->connection, $query_insert);}
        catch(mysqli_sql_exception){
            return false;
        }
        return mysqli_affected_rows($this->connection)>0;
    }
}

?>