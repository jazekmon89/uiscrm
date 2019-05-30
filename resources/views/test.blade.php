<?php
echo "<pre>Stored Procedure: ".$sp."</pre>";
echo "<pre>Data:<br />".var_export($data,1)."</pre>";
echo "<pre>Returned value: ".var_export($result, 1)."</pre>";
echo "<pre>Out: ".var_export($out,1 )."</pre>";
?>