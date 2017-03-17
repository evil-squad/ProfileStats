//chenjing
package com.game.engine3d.utils
{
	import flash.display.Stage;
	import flash.display.Stage3D;
	import flash.events.Event;
	import flash.events.EventDispatcher;
	import flash.events.IOErrorEvent;
	import flash.events.TimerEvent;
	import flash.external.ExternalInterface;
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.net.URLVariables;
	import flash.system.Capabilities;
	import flash.utils.Timer;
	import flash.utils.getTimer;
	
	public class LogUtils extends EventDispatcher
	{
		private static var _stage3dIndex:int; 
		private static var _stage:Stage; 
		private static var _stage3d:Stage3D; 
		private static var _profile:String;
		private static var _clientName:String;
		public static var infoStr:String;
		private static var _extraInfo:String;
		private static var variables:URLVariables = new URLVariables;
		private static var _context3DProxy:GetContext3D;
		
		private static var _logFr:Boolean;
		
		private static const LOG_FRAMEFRATE_INTERVAL:int = 5 * 60 * 1000; //每隔5 minutes发一次
		private static var _frameCount:int;
		private static var _timer:Timer;
		private static var _totalActiveTime:int;		
		private static var _lastFrameTime:int;
		private static var _logStartTime:int;
		private static var _logCount:int;
		
		private static const ONE_MINUTE:int = 60 * 1000;
		private static var _maxFrameRateInOneMinute:Number = 0;
		private static var _frameCountInOneMinute:int;
		private static var _timeInOneMinute:int;
		
		private static var _enableFrameCounter:Boolean = true;
		
		private static var _idInfo:String = null;
		
		[Embed(source="stat.js", mimeType="application/octet-stream")]
		private static var JS_DATA:Class;
		
		public static function log2D(stage:Stage, clientName:String, extra:String=null, logFrameRate:Boolean = false):void
		{
			_stage = stage;
			_stage3d = null;
			_stage3dIndex = -1;
			_clientName = clientName;
			_extraInfo = extra;
			infoStr = null;
			_logFr = logFrameRate;
			
			log();
			
			if(_logFr)
				logFR(stage);
		}
		
		public static function log3D(profile:String, stage3d:Stage3D, clientName:String,
									 extra:String=null, logFrameRate:Boolean = false, stage:Stage = null):void 
		{			
			_stage3d = stage3d;
			_stage = null;
			_profile = profile;
			_stage3dIndex = -1;
			_clientName = clientName;
			_extraInfo = extra;
			infoStr = null;
			_logFr = logFrameRate && stage;
			variables.idInfo = null;

			log();
			
			if(_logFr)
				logFR(stage);
		} 
		
		public static function stopLogFrameRate():void 
		{			
			dispose();
		} 
		
		private static function logFR(stage:Stage):void 
		{			
			_stage = stage;
			_frameCount = 0;
			_stage.addEventListener(Event.ENTER_FRAME, onEnterFrame, false, 0, true);
			_stage.addEventListener(Event.ACTIVATE, activateFrameCounter, false, 0, true);
			_stage.addEventListener(Event.DEACTIVATE, deactivateFrameCounter, false, 0, true);
			
			_timer = new Timer(LOG_FRAMEFRATE_INTERVAL);
			_timer.addEventListener(TimerEvent.TIMER, logTimerFR);
			_logStartTime = getTimer();
			_timer.start(); 
		} 
		
		private static function activateFrameCounter(event:Event):void
		{
			_enableFrameCounter = true;
		}
		
		private static function deactivateFrameCounter(event:Event):void
		{
			_enableFrameCounter = false;
		}
		
		private static function logTimerFR(event:Event):void
		{
			if(_totalActiveTime > 0 && _frameCount > 0)
			{
				var fr:Number = _frameCount * 1000 / _totalActiveTime;
				variables.frameRate = Number(fr).toFixed(1);
				variables.logCount = _logCount;
				//minutes
				variables.runningTime = Number((getTimer() - _logStartTime) / 60000).toFixed(1);
				variables.maxFrameRate = Number(_maxFrameRateInOneMinute).toFixed(1);
				sendLog(true);
				_logCount ++;
			}
			
			_frameCount = 0;
			_totalActiveTime = 0;
		}
		
		private static function onEnterFrame(event:Event):void
		{
			var time:int = getTimer();
			var interval:int = time - _lastFrameTime;
			if(_enableFrameCounter)
			{
				_frameCount ++;		
				_totalActiveTime += interval;
			}
			_lastFrameTime = time;
			
			_frameCountInOneMinute ++;
			_timeInOneMinute += interval;
			if(_timeInOneMinute >= ONE_MINUTE)
			{
				var fr:int = _frameCountInOneMinute * 1000 / _timeInOneMinute;
				_frameCountInOneMinute = 0;
				_timeInOneMinute = 0;
				if(fr > _maxFrameRateInOneMinute)
					_maxFrameRateInOneMinute = fr;
			}
		}
		
		private static function log():void
		{
			if(_stage3d && _stage3d.context3D)
			{
				logResult(true, _profile, _stage3d);
			}
			else if(_stage3d)
			{
				logResult(false, _profile, _stage3d);
			}
			else
			{				
				if(_stage)
				{
					for(_stage3dIndex = 0; _stage3dIndex < _stage.stage3Ds.length; _stage3dIndex ++)
					{
						if(_stage.stage3Ds[_stage3dIndex] && _stage.stage3Ds[_stage3dIndex].context3D)
							continue;
						else if (_stage.stage3Ds[_stage3dIndex])
						{
							_stage3d = _stage.stage3Ds[_stage3dIndex];
							break;
						}
					}
					if(_stage3d)
					{
						_context3DProxy = new GetContext3D;
						_context3DProxy.getContext3D(_stage3d, logResult);
					}
				}
			}
		}
		
		private static function logResult(success:Boolean, profile:String, stage3d:Stage3D):void 
		{
			getJsParams(variables);
			variables.version = Capabilities.version;
			variables.sys = Capabilities.os;
			variables.player = Capabilities.playerType;
			variables.clientName = _clientName;
			variables.isDebugger = Capabilities.isDebugger;
			
			if(_extraInfo != null)
				variables.extraInfo = _extraInfo;
			
			if(success)
			{			
				variables.profile = profile;
				variables.driver = stage3d.context3D.driverInfo;
				variables.width = stage3d.context3D.backBufferWidth;
				variables.height = stage3d.context3D.backBufferHeight;
			}
			else
			{
				variables.profile = profile;
				variables.driver = null;
				variables.width = 0;
				variables.height = 0;
			}
			
			infoStr = "version: " + variables.version + "\n" +
				"system: " + variables.sys + "\n" +
				"player: " + variables.player + "\n" +
				"clientName: " + variables.clientName + "\n" +
				"profile: " + variables.profile + "\n" +
				"driver: " + variables.driver + "\n" +
				"renderer: " + variables.renderer + "\n" +
				"glRenderer: " + variables.glRenderer + "\n" +
				"cpu core: " + variables.concurrency + "\n" +
				"userAgent: " + variables.userAgent + "\n" +
				"glVendor: " + variables.glVendor;
			
			sendLog();
	
			if(_stage)
				_stage.dispatchEvent(new Event("LogComplete"));
			
			if(!_logFr)
				dispose();
		}
		
		private static function sendLog(logFrameRate:Boolean = false):void 
		{
			var url:String = "http://120.26.54.202:8080/VersionLog/VersionLog8.jsp?fr=" + logFrameRate;
			var request:URLRequest = new URLRequest(url);
			request.data = variables;
			request.method = "POST";
			var urlLoader:URLLoader = new URLLoader();
			try
			{
				urlLoader.addEventListener(IOErrorEvent.IO_ERROR, urlLoaderIOError, false, 0, true);
				urlLoader.load(request);
			}
			catch (error:ArgumentError)
			{
				trace("An ArgumentError has occurred.");
			}
			catch (error:SecurityError)
			{
				trace("A SecurityError has occurred.");
			}
		}
		
		protected static function urlLoaderIOError(event:IOErrorEvent):void
		{
			// TODO Auto-generated method stub
			event.target.removeEventListener(IOErrorEvent.IO_ERROR, urlLoaderIOError);
		}
		
		private static function dispose():void
		{
			if(_stage3dIndex >= 0)
				_stage.stage3Ds[_stage3dIndex] = null;
			_stage = null;
			variables = null;
			if(_context3DProxy)
				_context3DProxy.dispose();
			_context3DProxy = null;
			if(_timer)
			{
				_timer.stop();
				_timer.removeEventListener(TimerEvent.TIMER, logTimerFR);
				_timer = null;
			}
			if(_stage && _stage.hasEventListener(Event.ENTER_FRAME))
				_stage.removeEventListener(Event.ENTER_FRAME, onEnterFrame);
			if(_stage && _stage.hasEventListener(Event.ACTIVATE))
				_stage.removeEventListener(Event.ACTIVATE, activateFrameCounter);
			if(_stage && _stage.hasEventListener(Event.DEACTIVATE))
				_stage.removeEventListener(Event.DEACTIVATE, deactivateFrameCounter);
			
			_logFr = false;
		}
		
		private static function getJsParams(variables:URLVariables):void
		{
			variables.renderer = "null";
			variables.glRenderer = "null";
			variables.concurrency = "null";
			variables.userAgent = "null";
			variables.glVendor = "null";
			
			if (ExternalInterface.available)  
			{
				try
				{
					variables.userAgent = ExternalInterface.call("eval", "navigator.userAgent");
					variables.concurrency = ExternalInterface.call("eval", "navigator.hardwareConcurrency");
				}
				catch(e:Error)
				{
					variables.userAgent = "error agent, " + e.message;
				}
				
				try
				{
					var jsStr:String = new JS_DATA();
					var info:String = ExternalInterface.call("eval", jsStr);
					if(info!="null")
					{
						if(info.indexOf("error") >= 0)
						{
							variables.renderer = info;
						}
						else
						{
							var strs:Array = info.split(";;");
							variables.renderer = strs[0];
							variables.glRenderer = strs[1];
							variables.glVendor = strs[2];
						}
					}
					else
						variables.renderer = "error return value of js is null";
				}
				catch(e:Error)
				{
					variables.renderer = "error " + e.message;
				}
			}
			else
			{
				variables.renderer = "error ExternalInterface not available"; 
			}
		}

		public static function get idInfo():String
		{
			return _idInfo;
		}

		public static function set idInfo(value:String):void
		{
			_idInfo = value;
			if(_logFr)
				variables.idInfo = value;
		}

	}
}
