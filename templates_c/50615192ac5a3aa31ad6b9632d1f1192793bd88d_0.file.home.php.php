<?php
/* Smarty version 3.1.29, created on 2016-02-27 21:05:09
  from "C:\xampp\htdocs\View\home.php" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_56d20175f1c839_53449290',
  'file_dependency' => 
  array (
    '50615192ac5a3aa31ad6b9632d1f1192793bd88d' => 
    array (
      0 => 'C:\\xampp\\htdocs\\View\\home.php',
      1 => 1456603077,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_56d20175f1c839_53449290 ($_smarty_tpl) {
?>
<div class="container">

    <?php if (isset($_smarty_tpl->tpl_vars['error']->value)) {?>
    <p>Error: <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['error']->value);?>
</p>
    <?php }?>



</div>

<?php }
}
