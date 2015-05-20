(function () {
  var service,
    dialogParams = {
      minWidth: 500,
      modal: true
    };

  angular.module('FileEditorModal', ['EntityService', 'FileEditor', 'angularModalService'])
    .run(['EntityService', function (EntityService) {
      service = new EntityService('files', 'id');
      service.fetch();
    }])
    .directive('fileEditorModal', ['ModalService', function(ModalService) {

      function link(scope, elem, attr) {
        elem.bind('click', clickHandler);
      }

      function clickHandler(event) {
        event.preventDefault();

        ModalService.showModal({
          template: '<div file-edit file="file" on-close="closeModal(saved)"></div>',
          controller: 'FileEditorModalController',
          inputs: {
            fid: this.attributes.fid.value
          }
        }).then(function (modal) {
          modal.element.dialog(dialogParams);
          modal.close.then(function(result) {
            if (result) {
              window.location.reload();
            }
          })
        });
      }
      return {
        link: link
      }
    }])
    .controller('FileEditorModalController', ['$scope', 'EntityService', 'fid', 'close', function ($scope, EntityService, fid, close) {
      $scope.file = angular.copy(service.get(fid));

      $scope.closeModal = function (saved) {
        close(saved);
      }
    }]);
})();