define('user/module/api/api', function(require, exports, module) {

"use strict";

module.exports = {
	agent: {
		pass: "/api/agent/auditPass",
		reject: "/api/agent/auditReject",
		lock: "/api/agent/lockAgent",
		unlock: "/api/agent/unlockAgent",
		del: "/api/agent/delAgent"
	},
	team: {
		pass: "/api/carTeam/auditPass",
		reject: "/api/carTeam/auditReject",
		lock: "/api/carTeam/lockCarTeam",
		unlock: "/api/carTeam/unlockCarTeam",
		del: "/api/carTeam/delCarTeam"
	}
};

});
