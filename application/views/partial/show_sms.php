<?php

if ($this->session->userdata('success')) {
    echo $this->session->userdata('success');
    $this->session->unset_userdata('success');
} else if ($this->session->userdata('error')) {
    echo $this->session->userdata('error');
    $this->session->unset_userdata('error');
}
?>
<div id="feedback_bar"></div>