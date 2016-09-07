<?php


if ( ! defined( 'ABSPATH' ) ) exit;


class Mondula_Form_Wizard_Wizard {

    private $_steps = array();
    private $_maildata = array();

    public function __construct() {
    }

    public function get_maildata(){
      return $this->_maildata;
    }

    public function set_maildata ( $maildata ) {
      $this->_maildata = $maildata;
    }

    /**
     *
     * @param $array $elements Elements of the step to add
     * @return void
     */
    public function add_step ( $steps ) {
        $this->_steps[] = $steps;
    }

    private function _get_class ( $len ) {
        // PRO FEATURE
        // switch ( $len ) {
        //     case 3:
        //         return 'fw-one_third';
        //     case 2:
        //         return 'fw-one_half';
        //     default:
        //         return '';
        // }
        return '';
    }

    private function fw_get_option($option, $section, $default = '') {
      $options = get_option($section);
      if ( isset( $options[$option] ) )
		    echo $options[$option];
	    else
		    return $default;
    }

    private function render_progress_bar () {
        $cnt = count( $this->_steps );
        ?>
<div class="fw-progress-wrap">

    <ul class="fw-progress-bar"
        data-activecolor="<?php $this->fw_get_option('activecolor' ,'fw_settings_styling', '#57aed1');?>"
        data-donecolor="<?php $this->fw_get_option('donecolor' ,'fw_settings_styling', '#57aed1');?>"
        data-nextcolor="<?php $this->fw_get_option('nextcolor' ,'fw_settings_styling', '#57aed1');?>">
        <?php
        for ($i = 0; $i < $cnt; $i++) {
            $step = $this->_steps[$i];
            ?>
        <li class="fw-progress-step"
            data-id="<?php echo $i; ?>">
            <?php echo $step->render_title(); ?></li>
            <?php
        }
        ?>
    </ul>
</div>
        <?php
    }

    private function render_step_title ( $parts ) {
        $width = $this->_get_class( count($parts) );
?>
<div class="fw-step-title">
    <?php
        $len = count($parts);
        for ($i = 0; $i < $len; $i++) {
            $part = $parts[$i];
            if ($i > 0 && $part->same_title($parts[$i - 1])) {
                $class = $width . ' fw-title-hidden';
            } else {
                $class = $width;
            }
            ?>
    <div class="fw-step-part-title <?php echo $class; ?>">
            <?php
                $part->render_title();
            ?>
    </div>
            <?php
        }
    ?>
</div>
<?php
    }

    private function render_step_body ( $parts ) {
        $class = $this->_get_class( count($parts) );
        ?>
        <div class="fw-step-body">
            <?php
                $cnt = count( $parts );
                for ( $i = 0; $i < $cnt; $i++ ) {
                    ?>
            <div class="fw-step-part <?php echo $class; ?>" data-partId="<?php echo $i; ?>">
                    <?php
                        $part = $parts[$i];
                        $part->render_body( $i );
                    ?>
            </div>
                    <?php
                }
            ?>
        </div>
        <?php
    }

    private function render_step_parts ( $parts ) {
        $cnt = count( $parts );
        $width = $this->_get_class( $cnt );

        for ($i = 0; $i < $cnt; $i++) {
            $part = $parts[$i];
            if ($i > 0 && $part->same_title($parts[$i - 1])) {
                $hidden = ' fw-title-hidden';
            } else {
                $hidden = '';
            }
            ?>
            <div class="fw-step-part <?php echo $width; ?>" data-partId="<?php echo $i ?>">
                <div class="fw-step-part-title <?php echo $hidden; ?>">
                        <?php
                            $part->render_title();
                        ?>
                </div>
                <div class="fw-step-part-body">
                        <?php
                            $part->render_body( $i );
                        ?>
                </div>
            </div>
            <?php
        }
    }

    /**
     *
     */
    public function render ( $wizardId ) {
        ob_start();
        ?>
        <div id="mondula-form-wizard" class="fw-wizard" data-stepCount="<?php echo count( $this->_steps )?>" data-wizardid="<?php echo $wizardId ?>">
            <div class="fw-progress-bar-container">
                <div class="fw-container">
            <?php
                $this->render_progress_bar( $this->_steps );
            ?>
                </div>
            </div>
            <div class="fw-wizard-step-header-container">
                <div class="fw-container">
                <?php
                $len = count( $this->_steps );
                for ($i = 0; $i < $len; $i++) {
                    $step = $this->_steps[$i];
                    ?>
                <div class="fw-wizard-step-header" data-stepId="<?php echo $i; ?>">
                    <h2><?php echo $step->render_headline(); ?></h2>
                    <p class="fw-copytext"><?php $step->render_copy_text(); ?></p>
                </div>
                <?php
                }
                ?>
                </div>
            </div>
            <div class="fw-wizard-step-container">
                <div class="fw-container">
            <?php
                for ($i = 0; $i < $len; $i++) {
                    $step = $this->_steps[$i];
                    ?>
                <div class="fw-wizard-step" data-stepId="<?php echo $i; ?>">
                    <?php
                        $step->render( $wizardId, $i );
                    ?>
                    <div class="fw-clearfix"></div>
                </div>
                <?php
                }
            ?>
                </div>
            </div>
            <?php if (count($this->_steps) > 1) { ?>
            <div class="fw-wizard-button-container">
                <div class="fw-container">
                    <div class="fw-wizard-buttons">
                        <button class="fw-button-previous"><?php _e( 'Previous Step' ) ?></button>
                        <button class="fw-button-next"><?php _e( 'Next Step' ) ?></button>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php
        ob_end_flush();
    }

    private function render_header_html () {
        ?>
        <div><?php echo $this->_maildata['header']?></div>
        <?php
    }

    private function render_header () {
        echo $this->_maildata['header'] . PHP_EOL . PHP_EOL;
    }

    private function render_body ( $data, $name, $email ) {
        foreach ( $data as $key => $value ) {
            echo PHP_EOL .  $key . PHP_EOL . PHP_EOL;
            foreach ( $value as $value2 ) {
                foreach ( $value2 as $key2 => $value3 ) {
                    echo "\t" . $key2 . " - " . $value3 . PHP_EOL;
                }

//                $step = $this->_steps[$key];
//                $step[$key2]->render_mail( $value2 );
            }
            echo PHP_EOL;
        }

        echo PHP_EOL . "Name: " . $name . PHP_EOL;
        echo "Email: " . $email . PHP_EOL;
    }

    // TODO mail footer
    private function render_footer () {
        echo PHP_EOL . "End of form submission" . PHP_EOL;
    }

    // TODO html mail footer
    private function render_footer_html() {
       ?>
       <div>End of form submission</div>
       <?php
    }

    public function render_mail ( $data, $name, $email ) {
        ob_start();
        $this->render_header();
        $this->render_body( $data, $name, $email );
        $this->render_footer();
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    public function as_aa() {
        $steps_json = array();
        foreach ($this->_steps as $step) {
            $steps_json[] = $step->as_aa();
        }
        return array(
            'steps' => $steps_json,
            'mail' => $this->_maildata
        );
    }


}
