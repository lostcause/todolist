(function () {
    'use strict';

    angular.module('app')
        .factory('Auth', ['$http', '$localStorage', 'urls', function ($http, $localStorage, urls) {
            return {
                signup: function (data, success, error) {
                    $http.post(urls.BASE + '/auth/register', data).success(success).error(error);                    
                },
                signin: function (data, success, error) {
                    console.log('Sending login data');
                    $http.post(urls.BASE + '/auth/login', data).success(success).error(error);
                },
                logout: function (success) {
                    console.log('Removing token: ' + $localStorage.token);
                    delete $localStorage.token;
                    localStorage.removeItem('ngStorage-token');
                    console.log('Stored token: ' + $localStorage.token);
                    success();
                }
            };
        }
        ]);
})();