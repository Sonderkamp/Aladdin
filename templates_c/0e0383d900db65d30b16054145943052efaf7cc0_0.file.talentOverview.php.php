<?php
/* Smarty version 3.1.29, created on 2016-02-29 20:53:50
  from "C:\xampp\htdocs\View\talentOverview.php" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_56d4a1ced33512_48002403',
  'file_dependency' => 
  array (
    '0e0383d900db65d30b16054145943052efaf7cc0' => 
    array (
      0 => 'C:\\xampp\\htdocs\\View\\talentOverview.php',
      1 => 1456775628,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_56d4a1ced33512_48002403 ($_smarty_tpl) {
echo '<?php
';?>/**
 * Created by PhpStorm.
 * User: Joost
 * Date: 27-2-2016
 * Time: 21:48
 */
<?php echo '?>';?>

<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th>Talent</th>
                <th>Verwijderen</th>
            </tr>
        </thead>
        <tbody>
        <?php
$_from = $_smarty_tpl->tpl_vars['talents']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_talent_0_saved_item = isset($_smarty_tpl->tpl_vars['talent']) ? $_smarty_tpl->tpl_vars['talent'] : false;
$_smarty_tpl->tpl_vars['talent'] = new Smarty_Variable();
$_smarty_tpl->tpl_vars['talent']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['talent']->value) {
$_smarty_tpl->tpl_vars['talent']->_loop = true;
$__foreach_talent_0_saved_local_item = $_smarty_tpl->tpl_vars['talent'];
?>
            <tr>
                <td class="col-sm-8"><?php echo $_smarty_tpl->tpl_vars['talent']->value->talent;?>
</td>
                <td class="col-sm-1"><button type="button" class="btn btn-danger btn-sm">Verwijderen</button></td>
            </tr>
        <?php
$_smarty_tpl->tpl_vars['talent'] = $__foreach_talent_0_saved_local_item;
}
if ($__foreach_talent_0_saved_item) {
$_smarty_tpl->tpl_vars['talent'] = $__foreach_talent_0_saved_item;
}
?>
        </tbody>
    </table>
</div><?php }
}
