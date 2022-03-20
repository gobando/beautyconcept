/*
 * File name: salon_awards_controller.dart
 * Last modified: 2022.02.12 at 21:52:29
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:get/get.dart';

import '../../../../common/ui.dart';
import '../../../models/award_model.dart';
import '../../../models/salon_model.dart';
import '../../../repositories/salon_repository.dart';

class SalonAwardsController extends GetxController {
  final salon = Salon().obs;
  final awards = <Award>[].obs;

  SalonRepository _salonRepository;

  SalonAwardsController() {
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
    await refreshAwards();
    super.onReady();
  }

  Future refreshAwards({bool showMessage = false}) async {
    await getAwards();
    if (showMessage) {
      Get.showSnackbar(Ui.SuccessSnackBar(message: salon.value.name + " " + "page refreshed successfully".tr));
    }
  }

  Future getAwards() async {
    try {
      awards.assignAll(await _salonRepository.getAwards(salon.value.id));
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }
}
