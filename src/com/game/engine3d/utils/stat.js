if(!document)
{
	"error no document!";
}
else
{
	var body = document.body;
	var canvas = document.createElement("canvas");
	if(!canvas)
	{
		"error no canvas!";
	}
	else
	{
		var gl = canvas.getContext("webgl");
		if(!gl)
			gl = canvas.getContext("experimental-webgl");
		if(!gl)
		{
			"error no gl!";
		}
		else
		{
			var renderer = gl.getParameter(gl.RENDERER);
			var vendor = gl.getParameter(gl.VENDOR);
			var glRenderer = "null";
			var glVendor = "null";
			var dbgRenderInfo = gl.getExtension("WEBGL_debug_renderer_info");
			if(dbgRenderInfo != null)
			{
				glRenderer = gl.getParameter(dbgRenderInfo.UNMASKED_RENDERER_WEBGL);
				glVendor = gl.getParameter(dbgRenderInfo.UNMASKED_VENDOR_WEBGL);
			}
			if(!glRenderer)
				glRenderer = "null";
			if(!renderer)
				renderer = "null";
			if(!glVendor)
				glVendor = "null";
			renderer + ";;" + glRenderer + ";;" + glVendor;
		}
	}
}