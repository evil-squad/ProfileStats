package 
{
	
	import com.game.engine3d.utils.LogUtils;
	
	import flash.display.Sprite;
	import flash.display.StageAlign;
	import flash.display.StageQuality;
	import flash.display.StageScaleMode;
	import flash.events.Event;
	import flash.text.AntiAliasType;
	import flash.text.TextField;
	import flash.text.TextFormat;
	
	[SWF(width="900", height="800", backgroundColor="#ffffff", frameRate="30", quality="BEST")]
	public class ProfileTester extends Sprite
	{
		private var text:TextField;
		
		public function ProfileTester() 
		{
			init();
			LogUtils.log2D(stage, "ProfileTester_web");
			stage.addEventListener("LogComplete", changeText);
		}
		
		private function init():void
		{
			stage.scaleMode = StageScaleMode.NO_SCALE;
			stage.align = StageAlign.TOP_LEFT;			
			
			stage.quality = StageQuality.BEST;
			
			text = new TextField();
			text.defaultTextFormat = new TextFormat("Verdana", 15, 0xFF0000);
			text.antiAliasType = AntiAliasType.ADVANCED;
			text.width = 900;
			text.height = 800;
			text.selectable = false;
			text.wordWrap = true;
			text.mouseEnabled = false;
			text.text = "Your profile:" + "\n";	
			addChild(text);	
		}
		
		private function changeText(e:Event):void
		{
			stage.removeEventListener("LogComplete", changeText);
			text.text += LogUtils.infoStr;
		}
		
	}	
}