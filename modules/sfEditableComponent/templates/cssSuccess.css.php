/** frontend styles **/
.<?php echo $componentCssClassName ?> {
  background-color: #ffe;
}

.<?php echo $componentCssClassName ?>:hover {
  background-color: #ffd;
}

.<?php echo $componentCssClassName ?>:before {
	content: "[double-click to edit]";
	font-family: Arial, Helvetica, sans;
	font-size: 70%;
	color: #888;
	float: right;
	padding: .2em .2em 0 0;
}

/** Edition form **/
#sfEditableComponentForm {
}
  #sfEditableComponentForm textarea {
    width: 98%;
    height: 120px;
  }
  #sfEditableComponentForm p {
    text-align: right;
  }

/** Facebox redefinitions **/
#facebox .body {
  width: 640px;
}