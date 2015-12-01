
function debug() {
	if ( mainConfig["env"] == "dev" )
	{
	    var i;
	    for (i = 0; i < arguments.length; i++) 
	    {
	        console.log(arguments[i]);
	    }
	}
} 