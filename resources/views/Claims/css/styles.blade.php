<style>
.policy-wrapper {
	float: left;
	display: inline-block;
	width: 33%;
}
.display-text {
	font-size:25px !important;
	white-space: pre-wrap !important;
}
.spinner2 {
  position: absolute;
  background-color: rgba(0, 0, 0, .6) !important;
  margin: 0 auto;
  top: 0 !important;
  right: 0 !important;
  left: 0 !important; 
  height: 100% !important;
  width: 100% !important;
}
/* -------------------------------- 

Breadcrumb

-------------------------------- */
.cd-breadcrumb {
  width: 100%;
  max-width: 68%;
  padding: 0.5em 1em;
  margin: 1em auto;
  background-color: #edeff0;
  border-radius: .25em;
}
.cd-breadcrumb:after {
  content: "";
  display: table;
  clear: both;
}
.cd-breadcrumb li {
  display: inline-block;
  float: left;
  margin: 0.5em 0;
}
.cd-breadcrumb li::after {
  /* this is the separator between items */
  display: inline-block;
  content: '\00bb';
  margin: 0 .6em;
  color: #959fa5;
}
.cd-breadcrumb li:last-of-type::after {
  /* hide separator after the last item */
  display: none;
}
.cd-breadcrumb li > * {
  /* single step */
  display: inline-block;
  color: #2c3f4c;
}
.cd-breadcrumb li.current > * {
  /* selected step */
  color: #96c03d;
}

.cd-breadcrumb a {
	cursor: pointer;
}

.cd-breadcrumb.triangle li a {
  color: #2c3f4c !important;
}

.cd-breadcrumb.triangle li.current a {
  color: #96C03D !important;
}


@media only screen and (min-width: 768px) {

  .cd-breadcrumb, .cd-multi-steps {
    padding: 0 1.2em;
  }
  .cd-breadcrumb li, .cd-multi-steps li {
    margin: 1.2em 0;
  }
  .cd-breadcrumb li::after, .cd-multi-steps li::after {
    margin: 0 1em;
  }

  .cd-breadcrumb.triangle {
    /* reset basic style */
    background-color: transparent;
    padding: 0;
  }
  .cd-breadcrumb.triangle li {
    position: relative;
    padding: 0;
    margin: 4px 4px 4px 0;
  }
  .cd-breadcrumb.triangle li:last-of-type {
    margin-right: 0;
  }
  .cd-breadcrumb.triangle li > * {
    position: relative;
    padding: 1em .8em 1em 2.5em;
    color: #2c3f4c;
    background-color: #edeff0;
    /* the border color is used to style its ::after pseudo-element */
    border-color: #edeff0;
  }
  .cd-breadcrumb.triangle li.current > * {
    /* selected step */
    color: #ffffff;
    background-color: #96c03d;
    border-color: #96c03d;
  }
  .cd-breadcrumb.triangle li:first-of-type > * {
    padding-left: 1.6em;
    border-radius: .25em 0 0 .25em;
    /*width: 70%;*/
  }
  .cd-breadcrumb.triangle li:last-of-type > * {
    padding-right: 1.6em;
    border-radius: 0 .25em .25em 0;
  }
  .no-touch .cd-breadcrumb.triangle a:hover {
    /* steps already visited */
    color: #ffffff;
    background-color: #2c3f4c;
    border-color: #2c3f4c;
  }
  .cd-breadcrumb.triangle li::after, .cd-breadcrumb.triangle li > *::after {
    /* 
    	li > *::after is the colored triangle after each item
    	li::after is the white separator between two items
    */
    content: '';
    position: absolute;
    top: 0;
    left: 100%;
    content: '';
    height: 0;
    width: 0;
    /* 48px is the height of the <a> element */
    border: 23px solid transparent;
    border-right-width: 0;
    border-left-width: 20px;
  }
  .cd-breadcrumb.triangle li::after {
    /* this is the white separator between two items */
    z-index: 1;
    -webkit-transform: translateX(4px);
    -moz-transform: translateX(4px);
    -ms-transform: translateX(4px);
    -o-transform: translateX(4px);
    transform: translateX(4px);
    border-left-color: #ffffff;
    /* reset style */
    margin: 0;
  }
  .cd-breadcrumb.triangle li > *::after {
    /* this is the colored triangle after each element */
    z-index: 2;
    border-left-color: inherit;
  }

  @-moz-document url-prefix() {
    .cd-breadcrumb.triangle li::after,
    .cd-breadcrumb.triangle li > *::after {
      /* fix a bug on Firefix - tooth edge on css triangle */
      border-left-style: dashed;
    }
  }

   .cd-breadcrumb.triangle li a {
    color: #2c3f4c !important;
  }

  .cd-breadcrumb.triangle li.current a {
    color: #ffffff !important;
  }
}

/* End Breadcrumb  */
.claim-wrapper {
  background: #ffffff;
}
.not-cstm-tabs-default {
  padding: 0 0 0 !important;
  margin-bottom: 0px !important;
}

</style>
