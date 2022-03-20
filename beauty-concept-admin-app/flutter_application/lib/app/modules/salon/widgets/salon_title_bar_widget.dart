/*
 * File name: salon_title_bar_widget.dart
 * Last modified: 2022.02.05 at 14:54:14
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:flutter/material.dart';
import 'package:get/get.dart';

class SalonTitleBarWidget extends StatelessWidget implements PreferredSize {
  final Widget title;
  final Widget tabBar;

  const SalonTitleBarWidget({Key key, @required this.title, this.tabBar}) : super(key: key);

  Widget buildTitleBar() {
    return Column(
      children: [
        Container(
          margin: EdgeInsets.only(left: 20, right: 20, bottom: 10),
          padding: EdgeInsets.symmetric(horizontal: 20, vertical: 10),
          height: 110,
          width: double.infinity,
          decoration: BoxDecoration(
            color: Get.theme.scaffoldBackgroundColor,
            borderRadius: BorderRadius.circular(10),
            boxShadow: [
              BoxShadow(color: Get.theme.focusColor.withOpacity(0.1), blurRadius: 10, offset: Offset(0, 5)),
            ],
          ),
          child: title,
        ),
        tabBar
      ],
    );
  }

  @override
  Widget build(BuildContext context) {
    return buildTitleBar();
  }

  @override
  Widget get child => buildTitleBar();

  @override
  Size get preferredSize => new Size(Get.width, 160);
}
