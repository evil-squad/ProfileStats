function on_first_page()
{
	var form=document.forms["cm_first_form"];

// 	var username=form["username"].value;
// 	var password=form["password"].value;
//
// 	if(username==null||username==""){alert("username must be filled out");return;}
// 	if(password==null||password==""){alert("password must be filled out");return;}

	form["width"	].value=document.body.clientWidth;
	form["height"	].value=document.body.clientHeight;

	form.submit();
}
