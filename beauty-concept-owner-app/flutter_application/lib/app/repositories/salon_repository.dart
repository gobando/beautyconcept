import 'package:get/get.dart';

import '../models/award_model.dart';
import '../models/e_service_model.dart';
import '../models/experience_model.dart';
import '../models/review_model.dart';
import '../models/salon_model.dart';
import '../models/user_model.dart';
import '../providers/laravel_provider.dart';

class SalonRepository {
  LaravelApiClient _laravelApiClient;

  SalonRepository() {
    this._laravelApiClient = Get.find<LaravelApiClient>();
  }

  Future<Salon> get(String salonId) {
    return _laravelApiClient.getSalon(salonId);
  }

  Future<List<Salon>> getAll() {
    return _laravelApiClient.getSalons();
  }

  Future<List<Review>> getReviews() {
    return _laravelApiClient.getSalonReviews();
  }

  Future<Review> getReview(String reviewId) {
    return _laravelApiClient.getSalonReview(reviewId);
  }

  Future<List<Award>> getAwards(String salonId) {
    return _laravelApiClient.getSalonAwards(salonId);
  }

  Future<List<Experience>> getExperiences(String salonId) {
    return _laravelApiClient.getSalonExperiences(salonId);
  }

  Future<List<EService>> getEServices({int page}) {
    return _laravelApiClient.getSalonEServices(page);
  }

  Future<List<User>> getEmployees(String salonId) {
    return _laravelApiClient.getSalonEmployees(salonId);
  }

  Future<List<EService>> getPopularEServices({int page}) {
    return _laravelApiClient.getSalonPopularEServices(page);
  }

  Future<List<EService>> getMostRatedEServices({int page}) {
    return _laravelApiClient.getSalonMostRatedEServices(page);
  }

  Future<List<EService>> getAvailableEServices({int page}) {
    return _laravelApiClient.getSalonAvailableEServices(page);
  }

  Future<List<EService>> getFeaturedEServices({int page}) {
    return _laravelApiClient.getSalonFeaturedEServices(page);
  }
}
