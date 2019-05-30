<style>
	.required > label:after, .required > div > label:after{
		content: '*';
		font-size: 16px;
		color: red;
		font-weight: bolder;
	}
	.label {color: #636b6f;}
	.form-group{position: relative;margin-bottom: 20px;}
	.error.label-alert, .label-alert{
		color: red;
		text-align: left;
		width: 100%;
		display:block;
		position: absolute;
		bottom: -15px;
	}
	.question-disp .error{position: inherit;
	    display: block;
	    margin-bottom: 10px;
	    bottom: auto!important;
	}
	.wrapper {background-color: transparent!important;}
	.wrapper .option{width: 40%;display: inline-block;}
	table.table td, table.table th{border-color: #b5bebf!important;padding-left: 0!important;}
	button:active .cp-spinner,
	button.disabled .cp-spinner,
	button.spin .cp-spinner{
		display: inline-block!important;
	}
	.cp-spinner {
		display: none;
	    width: 12px;
	    height: 12px;
	    box-sizing: border-box;
	    position: relative;
	}
	.cp-eclipse {
	    width: 2px;
	    height: 2px;
	    box-sizing: border-box;
	    border-radius: 50%;
	    background: #f3d53f; 
	    margin: 10px;
	    animation: cp-eclipse-animate 1s ease-out infinite;
	    vertical-align: middle;
	}
	
	.cp-eclipse:before {
	    border-radius: 50%;
	    content: " ";
	    width: 20px;
	    height: 20px;
	    display: inline-block;
	    box-sizing: border-box;
	    border-top: solid 3px transparent;
	    border-right: solid 3px #f3d53f;
	    border-bottom: solid 3px transparent;
	    border-left: solid 3px transparent;
	    position: absolute;
	    top: -9px;
	    left: -9px;
	}
	.cp-eclipse:after {
	    border-radius: 50%;
	    content: " ";
	    width: 20px;
	    height: 20px;
	    display: inline-block;
	    box-sizing: border-box;
	    border-top: solid 3px transparent;
	    border-right: solid 3px transparent;
	    border-bottom: solid 3px transparent;
	    border-left: solid 3px #f3d53f;
	    position: absolute;
	    top: -9px;
	    right: -9px;
	}
	@keyframes cp-eclipse-animate {
	  0% {
	    transform: rotate(0deg);
	  }
	  100% {
	    transform: rotate(360deg);
	  }
	}
</style>