<?php
/* Smarty version 3.1.29, created on 2016-02-27 21:05:10
  from "C:\xampp\htdocs\View\error_view.php" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_56d20176e1d307_91140705',
  'file_dependency' => 
  array (
    '6d5aaf59146f593f2bfad54dce07ab2230c1f36d' => 
    array (
      0 => 'C:\\xampp\\htdocs\\View\\error_view.php',
      1 => 1456603077,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_56d20176e1d307_91140705 ($_smarty_tpl) {
?>
<div class="container">
<h1>
    ERROR VIEW!
</h1>
<p>
    <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['message']->value);?>

</p>
</div><?php }
}
