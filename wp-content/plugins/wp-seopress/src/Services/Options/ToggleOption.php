<?php

namespace SEOPress\Services\Options;

defined('ABSPATH') or exit('Cheatin&#8217; uh?');

use SEOPress\Constants\Options;

class ToggleOption {
    /**
     * @since 4.3.0
     *
     * @return array
     */
    public function getOption() {
        return get_option(Options::KEY_TOGGLE_OPTION);
    }

    /**
     * @since 4.3.0
     *
     * @param string $key
     *
     * @return mixed
     */
    public function searchOptionByKey($key) {
        $data = $this->getOption();

        if (empty($data)) {
            return null;
        }

        $keyComposed = sprintf('toggle-%s', $key);
        if ( ! isset($data[$keyComposed])) {
            return null;
        }

        return $data[$keyComposed];
    }

    /**
     * @since 4.4.0
     *
     * @return string
     */
    public function getToggleLocalBusiness() {
        return $this->searchOptionByKey('local-business');
    }
}
