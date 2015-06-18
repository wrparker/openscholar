(function () {
  var service,
    dialogParams = {
      minWidth: 800,
      modal: true
    };

  angular.module('FileEditorModal', ['EntityService', 'FileEditor', 'angularModalService', 'angularFileUpload'])
    .run(['EntityService', function (EntityService) {
      service = new EntityService('files', 'id');
      service.fetch();
    }])
    .directive('fileEditorModal', ['ModalService', function(ModalService) {

      function link(scope, elem, attr) {
        elem.bind('click', clickHandler);

        elem.parent().find('.fid').change(function (e) {
          // Media removes all click events on the edit button, so we have to add the handler again if we want
          // this to continue working.
          elem.bind('click', clickHandler);
          elem.attr('fid', e.target.value);
        })
      }

      function clickHandler(event) {
        event.preventDefault();
        event.stopPropagation();
        var fid = this.attributes.fid.value;

        ModalService.showModal({
          template: '<div file-edit file="file" on-close="closeModal(saved)"></div>',
          controller: 'FileEditorModalController',
          inputs: {
            fid: fid
          }
        }).then(function (modal) {
          modal.element.dialog(dialogParams);
          modal.close.then(function(result) {
            if (result) {
              window.location.reload();
            }
          })
        });

        return false;
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
