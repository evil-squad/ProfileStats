<%@ page language="java" import="java.util.*, java.io.*, java.util.logging.*, java.util.Date,
java.text.*, java.util.logging.Formatter" pageEncoding="utf-8"%>

<%
request.setCharacterEncoding("utf-8");
%>

<%
String logframerate = request.getParameter("fr");
String filename = new File(application.getRealPath("ProfileStatsLog.jsp")).getParent() 
				+ File.separator + "log" + File.separator;

if(logframerate.equals("true"))
{
	filename += "PSL_framerate.txt";
	writeLogToFile(request, filename, true);
}
else
{
	filename += "PSL_renderlog.txt";

	if(session.isNew())
	{
		writeLogToFile(request, filename, false);
	}
}
%>

<%!
public void writeLogToFile(HttpServletRequest request, String filename, boolean logframerate)
{
	Logger logger = Logger.getLogger("versionlog");  
	  
	FileHandler fh;  
	try 
	{  
		fh = new FileHandler(filename,4*1024*1024,20,true);  
        logger.addHandler(fh);//日志输出文件  
        //logger.setLevel(level);  
        fh.setFormatter(new VersionFormatter());//输出格式  
        //logger.addHandler(new ConsoleHandler());//输出到控制台  
        logger.log(Level.INFO, formatMessage(request, logframerate));  
        logger.removeHandler(fh);
        fh.close();
    } 
    catch (IOException e) 
    { 
   		logger.log(Level.SEVERE,"io error!!!!!!!!!!!!", e);  
	}   
}
%>

<%!
public String formatMessage(HttpServletRequest request, boolean logframerate) 
{
	String logString = "";

    //ip
 	logString += "ip=" + getIpAddr(request) + "\r\n";


	Enumeration enu = request.getParameterNames(); 
    String parametername = "";
    while(enu.hasMoreElements()) 
    { 
        parametername = (String)enu.nextElement(); 
		if(
			!parametername.equals("ip") && 
			!parametername.equals("fr") && 
			(
				logframerate || 
				!(
					parametername.equals("idInfo") || parametername.equals("frameRate") || parametername.equals("logCount") || 
					parametername.equals("runningTime")|| parametername.equals("maxFrameRate")
				)
			)
		)
        {
        	logString += parametername + "=";
         	logString += request.getParameter(parametername);
        	logString += "\r\n";
    	}
    }
		
	return logString;
}
%>

<%!
class VersionFormatter extends Formatter 
{
	@Override
	public String format(LogRecord record) 
	{ 
		String logString = "";
		logString += "LOGSTART\r\n";
		logString += "Date=";
		Date dNow = new Date();
		SimpleDateFormat ft = new SimpleDateFormat ("E yyyy.MM.dd 'at' hh:mm:ss a");
		logString += ft.format(dNow);
		logString += " \r\n";
		logString += record.getMessage();
		logString += "LOGEND \r\n";
		return logString;
	} 
}
%>
<%!
public String getIpAddr(HttpServletRequest request) 
{
	String ip = request.getHeader("X-Forwarded-For");
	if(ip == null || ip.length() == 0 || "unknown".equalsIgnoreCase(ip)) 
	{
	ip = request.getHeader("Proxy-Client-IP");
	}
	if(ip == null || ip.length() == 0 || "unknown".equalsIgnoreCase(ip)) 
	{
	ip = request.getHeader("WL-Proxy-Client-IP");
	}
	if(ip == null || ip.length() == 0 || "unknown".equalsIgnoreCase(ip))
	{
	ip = request.getRemoteAddr();
	}
	return ip;
}
%>