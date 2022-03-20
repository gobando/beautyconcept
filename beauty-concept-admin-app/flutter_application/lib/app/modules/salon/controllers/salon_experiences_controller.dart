/*
 * File name: salon_experiences_controller.dart
 * Last modified: 2022.02.12 at 21:52:29
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:get/get.dart';

import '../../../../common/ui.dart';
import '../../../models/experience_model.dart';
import '../../../models/salon_model.dart';
import '../../../repositories/salon_repository.dart';

class SalonExperiencesController extends GetxController {
  final salon = Salon().obs;
  final experiences = <Experience>[].obs;

  SalonRepository _salonRepository;

  SalonExperiencesController() {
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
    await refreshExperiences();
    super.onReady();
  }

  Future refreshExperiences({bool showMessage = false}) async {
    await getExperiences();
    if (showMessage) {
      Get.showSnackbar(Ui.SuccessSnackBar(message: salon.value.name + " " + "page refreshed successfully".tr));
    }
  }

  Future getExperiences() async {
    try {
      experiences.assignAll(await _salonRepository.getExperiences(salon.value.id));
    } catch (e) {
      Get.showSnackbar(Ui.ErrorSnackBar(message: e.toString()));
    }
  }
}
