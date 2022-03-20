/*
 * File name: salon_e_services_view.dart
 * Last modified: 2022.02.07 at 14:16:22
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../controllers/salon_e_services_controller.dart';
import '../widgets/services_list_widget.dart';

class SalonEServicesView extends GetView<SalonEServicesController> {
  @override
  Widget build(BuildContext context) {
    return ServicesListWidget();
  }
}
