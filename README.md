As a web programmer, you know form is an integral part of the web(infact, the web engine, almost all we do is based on form). Now, to create a complete form that is not easily hackable, you need to include `csrftoken`, escaping to prevent `XXS attacks`, and the potential complications of handling `one-to-many relationships` they’re really too tedious to code from scratch every time, and, if you forget the csrftoken or a call to htmlspecialchars, you have a security hole. So, I created this `Form plugin` that has methods for many of the elements that a form can contain, and you can easily add additional methods as needed.

*Usage*
**Installing the plug-in**
You can install the plug-in in two ways through
1. composer `composer require drw/php-form`(recommended)
2. You can download the package, extract it and it include or require it

**Supported HTML input type**
The supported input types include
The `form start`: begins the form and outputs the opening `<form ...>` tag, with two optional arguments. The first is
an array of values to be displayed, indexed by field name. Typically, it’s the` $_POST` array from another form submittal
or a row retrieved from the database. The second argument is the action, but almost always you want to go back to the
same file. The csrftoken is placed into every form.

`form end` finishes the form.
The text method: `text($field, $label/*could be null*/, $len/* default is 50*/, $placeholder/* Could also be null*/, $break/*default is true*/, $password/*this incdicates if the field is password or text*/, $value )`

The label method: `label($field, $label/*the label could be null*/, $break/*break could be true or false*/)`

The button method: `button($field, $label/*The label could be null*/, $break/*default is true but coud also be false)`

The hspace method: `hspace(/*It recieve no parameter*/)`

The checkbox method: `checkbox($field, $label/*this parameter could also be null*/, $break/*break could be true or false*/)`

The radio method: `radio($field, $label/*this parameter could also be null*/, $value, $break/*break could be true or false*/)`

The date plugin `date($field, $label/*this parameter could also be null*/, $break/*break could be true or false*/)`


This is an example of how to use it.

`require __DIR__ . '/path/to/file';// Supposing you use the second method to use this plugin,`

```
$f = new Form();// Start the form
$f->start($row);// It could be null(if the form is new),but if it is prepopulated(then you can include like the example) 
$f->hidden('id', $row['id'] ?? '');// The hidden method equates to the hidden input form
$f->text('last', 'Last Name:', 30, 'Last Name');// The text method equates the text input method
$f->text('first', 'First:', 20, 'First Name', false);
$f->text('street', 'Street:', 50, 'Street');
$f->text('city', 'City:', 20, 'City');
$f->text('state', 'State:', 10, 'State', false);
//$f->foreign_key('specialty_id', 'name', 'Specialty');
$f->radio('billing', 'Monthly', 'month');
$f->hspace(2);// Use to add 2 horizontal spaces to the form
$f->radio('billing', 'Yearly', 'year', false);
$f->hspace(2)// Use to add 2 horizontal spaces to the form;
$f->radio('billing', 'Recurring', 'recurring', false);
$f->menu('contact', 'Contact:',
array('phone', 'email', 'mail', 'none'), true, 'email');// This is use to add drop down menu
$f->checkbox('premium', 'Premium:', false);// THis is use to add checkbox to the form
$f->date('since', 'Member Since:', false); // This is use to add dater to the form
$f->button('action_save', 'Save');// This is use to add button to the form
$f->end();// This is use to end the form
```