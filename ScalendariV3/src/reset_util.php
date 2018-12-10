<?php
require_once __DIR__ . '/../config/globalconfig.php';

function resetDB()
{
    class Area{
        public function Area($id, $name, $description, $zone, $transports, $coordinates){
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
            $this->zone = $zone;
            $this->transports = $transports;
            $this->coordinates = $coordinates;
        }
    };
    
    class Site{
        public function Site($area, $name, $description, $coordinates, $calendars){
            $this->area = $area;
            $this->name = $name;
            $this->description = $description;
            $this->calendars = $calendars;
            $this->coordinates = $coordinates;
        }
    };
    
    $areas = array();
    $sites = array();
    
    $xmls = scandir(SOURCE_DIR); # scans all xml files ins SOURCE_DIR
    
    $id = 0;
    
    foreach ($xmls as $file) {
        
        $extension = substr($file, strpos($file, ".") + 1);
        
        if ($extension == "xml") {
            $xml = simplexml_load_file(SOURCE_DIR . $file);
            
            if ($xml == false) {
                die($file." l'xml non è ben formattato");
                return;
            }
            
            #check if xml starts with tag area
            if ($xml->getName() == "area") {
                
                # checks if it has all necessary attributes
                $zone_attributes = $xml->attributes();
                
                if ($zone_attributes['name'] == null) {
                    die($file." manca l'attributo non opzionale 'name' nel tag area");
                }
                if ($zone_attributes['zone'] == null) {
                    die($file." manca l'attributo non opzionale 'zone' (A, B, ...) nel tag area");
                    return;
                }
                
                $current_area = new Area($id++, $zone_attributes['name'], $zone_attributes['desc'], $zone_attributes['zone'], $zone_attributes['transports'], $zone_attributes['coords']);
                
                # childrens are sites
                foreach ($xml->children() as $element) {
                    
                    if ($element->getName() == "site") {
                        
                        # checks if it has all necessary attributes
                        $element_attributes = $element->attributes();
                        
                        if ($element_attributes['name'] == null) {
                            die($file." l'attributo non opzionale 'name' in un tag ". $element->getName());
                        }
                        
                        if (in_array($element_attributes['name'], $sites)) {
                            die($file." il sito con il nome" . $element_attributes['name'] . " è già presente nel database");
                        }
                        
                        if ($element_attributes['desc'] == null) {
                            die($file." il sito con il nome" . $element_attributes['name'] . " non ha la descrizione");
                        }
                        
                        $current_site = new Site($current_area, $element_attributes['name'], $element_attributes['desc'], $element_attributes['coords'], $element_attributes['calendars']);
                                                
                        array_push($sites, $current_site);
                    } else {
                        printerror("elemento non conosciuto: " . $element . getName());
                        return;
                    }
                }
                
                array_push($areas, $current_area);
            } else {
                die($file." l'xml deve partire con il tag zone");
                return false;
            }
        }
    }
    
    # check for duplicates
    $sitesIndexedByName = array();
    $duplicates = array();
    
    foreach ($sites as $site) {
        $name = $site->name."";
        if(isset($sitesIndexedByName[$name])){
            if(!isset($duplicates[$name])){
                $duplicates[$name] = array();
                array_push($duplicates[$name], $sitesIndexedByName[$name]);
            }
            array_push($duplicates[$name], $site->area->name);
        } else {
            $sitesIndexedByName[$name] = $site->area->name;
        }
    }
    
    global $servername, $dbusername, $dbpassword, $dbname;
    
    $dbConn = new PDO("mysql:host=$servername", $dbusername, $dbpassword); # opens the connection
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); # throw exception on error
    
    $dbConn->exec("DROP DATABASE IF EXISTS $dbname;"); # drops the database
    $dbConn->exec("CREATE DATABASE IF NOT EXISTS $dbname;"); # creates the database (reset)
    
    $dbConn->exec("USE $dbname;"); # creates the database (reset)
    
    $dbConn->exec("CREATE TABLE Area (
        ID int PRIMARY KEY,
        Name varchar(63),
        Description varchar(255),
        Zone varchar(1),
        Transports varchar(63)
    );");
    
    $dbConn->exec("CREATE TABLE Site (
        ID int PRIMARY KEY,
        Name varchar(63),
        Description varchar(255),
        Coords varchar(31),
        Calendars int,
        Done boolean,
        AreaID int,
        FOREIGN KEY (AreaID) REFERENCES Area(ID)
    );");
    
    $dbConn->exec("CREATE TABLE Home (
        ID int,
        Name varchar(63),
        Description varchar(255),
        Coord varchar(31)
    );");
    
    $dbConn->exec("CREATE TABLE Configuration (
        Uniq boolean PRIMARY KEY DEFAULT true,
        TimeStamp bigint,
        Year int
    );");
    
    $dbConn->exec("START TRANSACTION");
    
    # saving timestamp and year in Configuration
    $timeStamp = time();
    $year = 2018;
    $dbConn->exec("INSERT INTO Configuration (TimeStamp, Year) VALUES ($timeStamp, $year)");
    
    # prepare for faster insertion (area)
    $prepareResult = $dbConn->prepare("INSERT INTO Area (ID, Name, Description, Zone, Transports) VALUES (:id, :name, :description, :zone, :transports)");
    $prepareResult->bindParam(':id', $id);
    $prepareResult->bindParam(':name', $name);
    $prepareResult->bindParam(':description', $description);
    $prepareResult->bindParam(':zone', $zone);
    $prepareResult->bindParam(':transports', $transports);
    
    foreach ($areas as $current_area) {
        $id = $current_area->id;
        $name = $current_area->name;
        $description = $current_area->description;
        $zone = $current_area->zone;
        $transports = $current_area->transports;
        
        $prepareResult->execute();
    }
    
    
    # prepare for faster insertion (site)
    $prepareResult = $dbConn->prepare("INSERT INTO Site (ID, Name, Description, Coords, Calendars, Done, AreaID) VALUES (:id, :name, :description, :coords, :calendars, :done, :areaID)");
    $prepareResult->bindParam(':id', $id);
    $prepareResult->bindParam(':name', $name);
    $prepareResult->bindParam(':description', $description);
    $prepareResult->bindParam(':coords', $coords);
    $prepareResult->bindParam(':calendars', $calendars);
    $prepareResult->bindParam(':done', $done);
    $prepareResult->bindParam(':areaID', $areaID);
    
    $id = 0;
    foreach ($sites as $current_site) {
        $name = $current_site->name;
        $description = $current_site->description;
        $coords = $current_site->coordinates;
        $calendars = $current_site->calendars;
        $done = false;
        $areaID = $current_site->area->id;
        
        $prepareResult->execute();
        $id++;
    }
    
    $dbConn->exec("COMMIT");
    
    $dbConn = null; # closes the connection
    
    return $duplicates;
}