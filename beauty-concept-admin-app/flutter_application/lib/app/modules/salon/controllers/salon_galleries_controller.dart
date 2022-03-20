/*
 * File name: salon_galleries_controller.dart
 * Last modified: 2022.02.12 at 21:52:29
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:get/get.dart';

import '../../../../common/ui.dart';
import '../../../models/media_model.dart';
import '../../../models/salon_model.dart';
import '../../../repositories/salon_repository.dart';

class SalonGalleriesController extends GetxController {
  final salon = Salon().obs;
  final galleries = <Media>[].obs;

  SalonRepository _salonRepository;

  SalonGalleriesController() {
    _salonRepository = new SalonRepository();
  }

  @override
  void onInit() {
    var arguments = Get.arguments as Map<String, dynamic>;
    salon.value = arguments['salon'] as Salon;
    super.onInit();
  }

  @override
  void onReady() async {
    await refreshGalleries();
    super.onReady();
  }

  Future refreshGalleries({bool showMessage = false}) async {
    await getGalleries();
    if (showMessage) {
      Get.showSnackbar(Ui.SuccessSnackBar(message: salon.value.name + " " + "page refreshed successfully".tr));
    }
  }

  Future getGalleries() async {
    try {
      final _galleries = await _salonRepository.getGalleries(salon.value.id);
      galleries.assignAll(_galleries.map((e) {
        e.image.name = e.description;
        return e.image;
      }));
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }
}
