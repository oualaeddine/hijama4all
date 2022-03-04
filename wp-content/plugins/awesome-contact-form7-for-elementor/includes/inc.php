<?php
foreach (glob(BE_PACK_PATH."widgets/*.php") as $includes){
    require_once $includes;
}
