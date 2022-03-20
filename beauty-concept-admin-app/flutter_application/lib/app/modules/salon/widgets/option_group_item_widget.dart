/*
 * File name: option_group_item_widget.dart
 * Last modified: 2022.02.07 at 14:18:14
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../models/e_service_model.dart';
import '../../../models/option_group_model.dart';
import 'option_item_widget.dart';

class OptionGroupItemWidget extends StatelessWidget {
  OptionGroupItemWidget({
    @required OptionGroup optionGroup,
    @required EService eService,
  })  : _optionGroup = optionGroup,
        _eService = eService;

  final OptionGroup _optionGroup;
  final EService _eService;

  @override
  Widget build(BuildContext context) {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Wrap(
          spacing: 10,
          children: [
            Icon(
              Icons.create_new_folder_outlined,
              color: Get.theme.hintColor,
            ),
            Text(
              _optionGroup.name ?? '',
              maxLines: 1,
              overflow: TextOverflow.ellipsis,
              style: Get.textTheme.headline5,
            ),
          ],
        ).paddingSymmetric(vertical: 10),
        ListView.separated(
          scrollDirection: Axis.vertical,
          shrinkWrap: true,
          primary: false,
          padding: EdgeInsets.zero,
          itemCount: _optionGroup.options.length,
          separatorBuilder: (context, index) {
            return SizedBox(height: 6);
          },
          itemBuilder: (context, index) {
            var _option = _optionGroup.options.elementAt(index);
            return OptionItemWidget(option: _option, optionGroup: _optionGroup, eService: _eService);
          },
        ),
      ],
    );
  }
}
