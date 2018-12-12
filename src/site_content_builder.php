<?php
require_once __DIR__ . '/../config/globalconfig.php';
$img_directory = getRelativePath(getcwd(), ROOT_FOLDER . "resources/img/");

class SiteContentBuilder
{

    private $name;

    private $description;

    private $calendars;

    private $onMapClick = NULL;

    function SiteContentBuilder($name, $description, $calendars)
    {
        $this->name = $name;
        $this->description = $description;
        if ($calendars == NULL) {
            $this->calendars = "?";
        } else {
            $this->calendars = $calendars;
        }
    }

    function setOnMapClick($onMapClick)
    {
        $this->onMapClick = $onMapClick;
    }

    function display()
    {
        global $img_directory;
        ?>
<div class="title"><?php echo $this->name; ?></div>
<img class="arrow" onclick="toggleExpanded(this.parentNode); event.cancelBubble=true;" src="<?php echo $img_directory?>arrowDown.svg" />
<div <?php
        
if ($this->onMapClick != NULL) {
            echo 'onclick="' . $this->onMapClick . ';event.cancelBubble=true;"';
        }
        ?>
	class="missing">
<?php
        if ($this->onMapClick != NULL) {
            ?>
		<img class="map" src="../../../resources/img/map.svg" />
    <?php
        }
        ?>
<h2><?php echo $this->calendars; ?></h2>
</div>

<div class="siteDescription"><?php echo $this->description; ?></div>
<?php 
    }
}