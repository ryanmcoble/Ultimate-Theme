
App.config.push(function($stateProvider, $urlRouterProvider) {
	$urlRouterProvider.otherwise('/');
	$stateProvider
    
    /**
     * Authentication
     */
    .state('login', {
        url: '/login',
        templateUrl: '/partials/auth/login.html',
        controller: 'LoginController'
    })
    .state('signup', {
        url: '/signup',
        templateUrl: '/partials/auth/signup.html',
        controller: 'SignupController'
    })

    /**
     * Challenges
     */
    .state('home', {
        url: '/',
        templateUrl: '/partials/challenges/list.html',
        controller: 'ChallengeListController'
    })
    .state('challenges-create', {
        url: '/challenges/create',
        templateUrl: '/partials/challenges/create.html',
        controller: 'ChallengeCreateController'
    })
    .state('challenges-view', {
        url: '/challenges/:key',
        templateUrl: '/partials/challenges/view.html',
        controller: 'ChallengeViewController'
    })
    .state('challenges-edit', {
    	url: '/challenges/:key/edit',
    	templateUrl: '/partials/challenges/edit.html',
    	controller: 'ChallengeEditController'
    })
    .state('challenges-delete', {
        url: '/challenges/:key/delete',
        templateUrl: '/partials/challenges/delete.html',
        controller: 'ChallengeDeleteController'
    })

    /**
     * Challenge Types
     */
    .state('challenge_types', {
        url: '/challenge_types',
        templateUrl: '/partials/challenge_types/list.html',
        controller: 'ChallengeTypeListController'
    })
    .state('challenge_types-create', {
        url: '/challenge_types/create',
        templateUrl: '/partials/challenge_types/create.html',
        controller: 'ChallengeTypeCreateController'
     })
    .state('challenge_types-edit', {
        url: '/challenge_types/:key/edit',
        templateUrl: '/partials/challenge_types/edit.html',
        controller: 'ChallengeTypeEditController'
    })
    .state('challenge_types-delete', {
        url: '/challenge_types/:key/delete',
        templateUrl: '/partials/challenge_types/delete.html',
        controller: 'ChallengeTypeDeleteController'
    });
});
