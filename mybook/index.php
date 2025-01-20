<?php
// GD kütüphanesi etkin mi?
if (extension_loaded('gd') && function_exists('gd_info')) {
    echo "GD kütüphanesi etkinleştirilmiş.";
} else {
    echo "GD kütüphanesi etkin değil.";
}
