//chenjing
package com.game.engine3d.utils
{
	import flash.display.Stage3D;
	import flash.display3D.Context3DRenderMode;
	import flash.events.ErrorEvent;
	import flash.events.Event;
	import flash.utils.setTimeout;
	
	/**
	 * A simple way to get an optimal Context3D. That is, an extended profile
	 * context is the first priority, then a baseline profile, then a
	 * constrained profile, and finally a software profile.
	 * @author Jackson Dunstan, JacksonDunstan.com
	 */
	public class GetContext3D
	{
		private static const STANDARD_EXTENDED:String = "standardExtended";
		
		private static const STANDARD:String = "standard";
		
		private static const STANDARD_CONSTRAINED:String = "standardConstrained";
		
		// Context3DProfile.BASELINE_EXTENDED. Use a string literal instead of
		// the Context3DProfile class so that this class can still be compiled
		// for Flash Players before the Context3DProfile class existed.
		private static const BASELINE_EXTENDED:String = "baselineExtended";
		
		// Context3DProfile.BASELINE. Use a string literal instead of
		// the Context3DProfile class so that this class can still be compiled
		// for Flash Players before the Context3DProfile class existed.
		private static const BASELINE:String = "baseline";
		
		// Context3DProfile.BASELINE_CONSTRAINED. Use a string literal instead of
		// the Context3DProfile class so that this class can still be compiled
		// for Flash Players before the Context3DProfile class existed.
		private static const BASELINE_CONSTRAINED:String = "baselineConstrained";
		
		/** Profile to get */
		private static var profile:String;
		
		/** Render mode to get */
		private static var renderMode:String;
		
		/** Stage3D to get the context for */
		private static var stage3D:Stage3D;
		
		/** Callback to call when the context is acquired or an error occurs
		 *   that prevents the context from being acquired. Passed a success
		 *   Boolean and a message String if the callback function takes two or
		 *   more parameters. If the callback function takes only one parameter,
		 *   only the success Boolean is passed. If the callback function takes
		 *   no parameters, none are passed.
		 */
		private static var callback:Function;
		
		/**
		 * Get the best Context3D for a Stage3D and call the callback when done
		 * @param stage3D Stage3D to get the context for
		 * @param callback Callback to call when the context is acquired or an
		 *        error occurs that prevents the context from being acquired.
		 *        Passed a success Boolean and a message String if the callback
		 *        function takes two or more parameters. If the callback
		 *        function takes only one parameter, only the success Boolean is
		 *        passed. If the callback function takes no parameters, none are
		 *        passed.
		 * @throws Error If the given stage3D or callback is null
		 */
		public function getContext3D(stage3d:Stage3D, callbackFunc:Function):void
		{
			if (callbackFunc == null)
			{
				throw new Error("Callback can't be null");
			}
			//Stage3D must be non-null
			if (stage3d == null)
			{
				throw new Error("Stage3D can't be null");
			}
			
			stage3D = stage3d;
			callback = callbackFunc;
			
			// Start by trying to acquire the best kind of profile
			renderMode = Context3DRenderMode.AUTO;
			profile = STANDARD_EXTENDED;
			stage3D.addEventListener(Event.CONTEXT3D_CREATE, onContext3DCreated, false, 1001, false);
			stage3D.addEventListener(ErrorEvent.ERROR, onStage3DError, false, 1001, false);
			requestContext();
		}
		
		/**
		 * Call the callback function and clean up
		 * @param success If the context was acquired successfully
		 * @param message Message about the context acquisition
		 */
		private function callCallback(success:Boolean, message:String): void
		{
			// Release reference to the callback after this function completes
			var callbackFunc:Function = callback;
			
			// Pass as many arguments as the callback function requires
			var numArgs:uint = callbackFunc.length;
			switch (numArgs)
			{
				case 0: callbackFunc(); break;
				case 1: callbackFunc(success); break;
				case 2: callbackFunc(success, message); break;
				case 3: callbackFunc(success, message, stage3D); break;
			}
		}
		
		/**
		 * Request a context with the current profile
		 */
		private function requestContext(): void
		{
			try
			{
				// Only pass a profile when the parameter is accepted. Do this
				// dynamically so we can still compile for older versions.
				if (stage3D.requestContext3D.length >= 2)
				{
					stage3D["requestContext3D"](renderMode, profile);
				}
				else
				{
					stage3D.requestContext3D(renderMode);
				}
			}
			catch (err:Error)
			{
				
				// Failed to acquire the context. Fall back.
				fallback();
			}
		}
		
		/**
		 * Callback for when there is an error creating the context
		 * @param ev Error event
		 */
		private function onStage3DError(ev:ErrorEvent): void
		{
			if(renderMode == Context3DRenderMode.AUTO)
			{
				setTimeout(fallback, 0);
			}
			else
			{
				callCallback(false, "Stage3D error ID: " + ev.errorID);
			}
		}
		
		/**
		 * Callback for when the context is created
		 * @param ev CONTEXT3D_CREATE event
		 */
		private function onContext3DCreated(ev:Event): void
		{
			// Got a software driver
			var driverInfo:String = stage3D.context3D.driverInfo.toLowerCase();
			var gotSoftware:Boolean = driverInfo.indexOf("software") >= 0;
			if (gotSoftware)
			{
				// Trying to get a non-software profile
				if (renderMode == Context3DRenderMode.AUTO)
				{
					fallback();
				}
				else
				{
					// Trying to get software. Succeeded.
					callCallback(true, profile);
				}
			}
				// Didn't get a software driver
			else
			{
				callCallback(true, profile);
			}
		}
		
		/**
		 * Fall back to the next-best profile
		 */
		private function fallback(ev:Event = null): void
		{
			// Check what profile we were trying to get
			switch (profile)
			{				
				case STANDARD_EXTENDED:
					profile = STANDARD;
					requestContext();
					break;
				case STANDARD:
					profile = STANDARD_CONSTRAINED;
					requestContext();
					break;
				case STANDARD_CONSTRAINED:
					profile = BASELINE_EXTENDED;
					requestContext();
					break;
				// Trying to get extended profile. Try baseline.
				case BASELINE_EXTENDED:
					profile = BASELINE;
					requestContext();
					break;
				// Trying to get baseline profile. Try constrained.
				case BASELINE:
					profile = BASELINE_CONSTRAINED;
					requestContext();
					break;
				// Trying to get constrained profile. Try software.
				case BASELINE_CONSTRAINED:
					if(renderMode == Context3DRenderMode.AUTO)
					{
						profile = BASELINE;
						renderMode = Context3DRenderMode.SOFTWARE;
						requestContext();
					}
					break;
			}
		}
		
		public function dispose():void
		{
			stage3D.removeEventListener(Event.CONTEXT3D_CREATE, onContext3DCreated);
			stage3D.removeEventListener(ErrorEvent.ERROR, onStage3DError);
			stage3D = null;
			callback = null;
		}
	}
}