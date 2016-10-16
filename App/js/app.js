/**
 * 
 * ，
 * 
 **/

(function($, owner) {
	
	owner.createState = function(loginInfo, callback) {
		var state = owner.getState();
		state.account = loginInfo;
		state.token = "token123456789";
		owner.setState(state);
		return callback();
	};
	
	/**
	 * 设置用户ID
	 * @param {Object} userId
	 */
	owner.setUserId = function(userId) {
		userId = userId || null;
		localStorage.setItem('$userId', userId);
	};
	
	/**
	 * 获取用户ID
	 */
	owner.getUserId = function() {
		var userId = localStorage.getItem('$userId') || null;
		return userId;
	};
	
	/**
	 * 获取当前状态
	 **/
	owner.getState = function() {
		var stateText = localStorage.getItem('$state') || "{}";
		return JSON.parse(stateText);
	};
	
	/**
	 * 设置当前状态
	 **/
	owner.setState = function(state) {
		state = state || {};
		localStorage.setItem('$state', JSON.stringify(state));
	};
	
	/**
	 * 保存设置
	 * @param {Object} settings
	 */
	owner.setSettings = function(settings) {
		settings = settings || {};
		localStorage.setItem('$settings', JSON.stringify(settings));
	};
	
	/**
	 * 获取设置
	 */
	owner.getSettings = function() {
		var settingsText = localStorage.getItem('$settings') || "{}";
		return JSON.parse(settingsText);
	};
	
	owner.toLogin = function() {
		setTimeout(function() {
			$.openWindow({
				url: 'login.html',
				id: 'login',
				show: {
					aniShow: 'pop-in'
				},
				waiting: {
					autoShow: false
				}
			});
		}, 0);
	};
	
	//获取城市
	owner.getCity = function() {
		var stateText = localStorage.getItem('$city') || "{}";
		return JSON.parse(stateText);
	};
	
	//设置城市
	owner.setCity= function(state) {
		state = state || {};
		localStorage.setItem('$city', JSON.stringify(state));
	};
	
	
	
	
}(mui, window.app = {}));