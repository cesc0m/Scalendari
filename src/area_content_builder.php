<?php
require_once __DIR__ . '/../config/globalconfig.php';
$img_directory = getRelativePath(getcwd(), ROOT_FOLDER . "resources/img/");

class AreaContentBuilder
{

    private $name;

    private $zone;

    private $transports;

    private $calendars = NULL;

    private $onMapClick = NULL;

    private $onArrowClick = NULL;

    function AreaContentBuilder($name, $zone, $transports)
    {
        $this->name = $name;
        $this->zone = $zone;
        $this->transports = $transports;
    }

    function setOnMapClick($onMapClickJsFunction)
    {
        $this->onMapClick = $onMapClickJsFunction;
        return $this;
    }

    function setOnArrowClick($onArrowClickJSFunction)
    {
        $this->onArrowClick = $onArrowClickJSFunction;
        return $this;
    }

    function display()
    {
        global $img_directory; // to have access to the global variable $img_directory
        
        ?>

<h1 class="title"><?php echo $this->name; ?></h1>

<?php
        if ($this->onArrowClick != NULL) {
            ?>
<img class="arrow"
	onclick="event.stopPropagation();<?php echo $this->onArrowClick; ?>"
	src="<?php echo $img_directory.'arrowDown.svg'?>" />
<?php
        }
        
        if (sizeof($this->transports) > 0) {
            ?>
<div class="transports">
		<?php
            foreach ($this->transports as $tmp_transport) {
                $transport_src_image;
                switch ($tmp_transport) {
                    case 'bike':
                        $transport_src_image = $img_directory . 'bike.svg';
                        break;
                    case 'moped':
                        $transport_src_image = $img_directory . 'moped.svg';
                        break;
                    case 'car':
                        $transport_src_image = $img_directory . 'car.svg';
                        break;
                    case 'feet':
                        $transport_src_image = $img_directory . 'feet.svg';
                        break;
                    default:
                        $transport_src_image = NULL;
                }
                if ($transport_src_image != NULL) {
                    ?>
                <img class="transport"
		src="<?php echo $transport_src_image; ?>" />
                <?php
                }
            }
            ?>
		</div>
<?php
        }
        ?>
<div <?php
        if ($this->onMapClick != NULL) {
            ?>
	onclick="event.stopPropagation();<?php echo $this->onMapClick; ?>"
	<?php
        }
        ?> class="missing">
        <?php
        if ($this->onMapClick != NULL) {
            ?>
            <img class="map" src="<?php echo $img_directory."map.svg"?>" />
            <?php
        }
        ?>
        <h2>?</h2>
</div>
<?php
    }
}