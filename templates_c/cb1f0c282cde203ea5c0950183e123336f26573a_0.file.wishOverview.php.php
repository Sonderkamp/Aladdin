<?php
/* Smarty version 3.1.29, created on 2016-02-27 21:27:11
  from "C:\xampp\htdocs\View\wishOverview.php" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_56d2069f2a0f22_83278251',
  'file_dependency' => 
  array (
    'cb1f0c282cde203ea5c0950183e123336f26573a' => 
    array (
      0 => 'C:\\xampp\\htdocs\\View\\wishOverview.php',
      1 => 1456604816,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_56d2069f2a0f22_83278251 ($_smarty_tpl) {
?>
<!--/**-->
<!-- * Created by PhpStorm.-->
<!-- * User: Max-->
<!-- * Date: 25-Feb-16-->
<!-- * Time: 15:12-->
<!-- */-->

<div class="container">
    <table class="table">
        <thead>
        <tr>
            <th>Gebruiker</th>
            <th>Naam</th>
            <th>Land</th>
            <th>Stad</th>
        </tr>
        </thead>
        <tbody>
        <?php
$_from = $_smarty_tpl->tpl_vars['wishes']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_wish_0_saved_item = isset($_smarty_tpl->tpl_vars['wish']) ? $_smarty_tpl->tpl_vars['wish'] : false;
$_smarty_tpl->tpl_vars['wish'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['wish']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['wish']->value) {
$_smarty_tpl->tpl_vars['wish']->_loop = true;
$__foreach_wish_0_saved_local_item = $_smarty_tpl->tpl_vars['wish'];
?>
        <tr>
            <td><?php echo $_smarty_tpl->tpl_vars['wish']->value->user;?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['wish']->value->name;?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['wish']->value->country;?>
</td>
            <td><?php echo $_smarty_tpl->tpl_vars['wish']->value->city;?>
</td>
        </tr>
        <?php
$_smarty_tpl->tpl_vars['wish'] = $__foreach_wish_0_saved_local_item;
}
if ($__foreach_wish_0_saved_item) {
$_smarty_tpl->tpl_vars['wish'] = $__foreach_wish_0_saved_item;
}
?>
        </tbody>
    </table>
</div><?php }
}
