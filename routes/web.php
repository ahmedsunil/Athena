<?php

use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\WebsiteController;
use App\Livewire\AppManagement\SystemSettings;
use App\Livewire\Auditing\ActivityLog;
use App\Livewire\Auditing\ActivityLogShow;
use App\Livewire\Cms\FooterLinks\FooterLinksIndex;
use App\Livewire\Cms\Announcements\AnnouncementsIndex;
use App\Livewire\Cms\HomeQuickAccess\HomeQuickAccessIndex;
use App\Livewire\Cms\HomeSlides\HomeSlidesIndex;
use App\Livewire\Cms\HomeStats\HomeStatsIndex;
use App\Livewire\Cms\HomeTestimonials\HomeTestimonialsIndex;
use App\Livewire\Cms\Icons\IconsIndex;
use App\Livewire\Cms\Links\LinksIndex;
use App\Livewire\Cms\SchoolProfile\SchoolProfileEdit;
use App\Livewire\Dashboard;
use App\Livewire\Profile\ProfileSettings;
use App\Livewire\Roles\RoleForm;
use App\Livewire\Roles\RoleShow;
use App\Livewire\Roles\RolesList;
use App\Livewire\Users\UserForm;
use App\Livewire\Users\UserShow;
use App\Livewire\Users\UsersList;
use App\Livewire\Website\Home;
use App\Livewire\Website\Announcements;
use App\Livewire\Website\AnnouncementShow;
use App\Livewire\Cms\Events\EventsIndex;
use App\Livewire\Website\Events;
use App\Livewire\Website\EventShow;
use App\Livewire\Cms\About\MissionEdit;
use App\Livewire\Cms\About\LeadershipIndex;
use App\Livewire\Cms\About\FoundingMembersIndex;
use App\Livewire\Cms\About\HistorySectionsIndex;
use App\Livewire\Cms\About\AchievementsIndex;
use App\Livewire\Cms\About\StaffIndex;
use App\Livewire\Website\About;
use App\Livewire\Cms\Academics\AcademicsOverviewEdit;
use App\Livewire\Cms\Academics\AcademicLevelsIndex;
use App\Livewire\Website\Academics;
use App\Livewire\Cms\StudentLife\ClubsIndex;
use App\Livewire\Cms\StudentLife\PrefectsIndex;
use App\Livewire\Cms\StudentLife\HousesIndex;
use App\Livewire\Cms\StudentLife\UniformBodiesIndex;
use App\Livewire\Cms\StudentLife\PeopleIndex;
use App\Livewire\Website\StudentLife;
use App\Livewire\Website\StudentLifeShow;
use App\Livewire\Cms\Gallery\GalleryIndex;
use App\Livewire\Cms\DigitalServices\DocumentsIndex;
use App\Livewire\Cms\DigitalServices\ResourcesIndex;
use App\Livewire\Cms\DigitalServices\CalendarEntriesIndex;
use App\Livewire\Website\DigitalServices;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', ProfileSettings::class)->name('profile.index');

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        // User management
        Route::get('/users', UsersList::class)->name('users.index');
        Route::get('/users/create', UserForm::class)->name('users.create');
        Route::get('/users/{userId}', UserShow::class)->whereNumber('userId')->name('users.show');
        Route::get('/users/{userId}/edit', UserForm::class)->name('users.edit');
        // Roles management
        Route::get('/roles', RolesList::class)->name('roles.index');
        Route::get('/roles/create', RoleForm::class)->name('roles.create');
        Route::get('/roles/{roleId}', RoleShow::class)->whereNumber('roleId')->name('roles.show');
        Route::get('/roles/{roleId}/edit', RoleForm::class)->name('roles.edit');

        // App Management
        Route::get('/app/activity', ActivityLog::class)->name('app.activity');
        Route::get('/app/activity/{activityId}', ActivityLogShow::class)
            ->whereNumber('activityId')
            ->name('app.activity.show');
        Route::get('/app/settings', SystemSettings::class)->name('app.settings');

        // CMS — config registries
        Route::get('/cms/links', LinksIndex::class)->name('cms.links.index');
        Route::get('/cms/icons', IconsIndex::class)->name('cms.icons.index');

        // CMS — content modules
        Route::get('/cms/school-profile', SchoolProfileEdit::class)->name('cms.school-profile');
        Route::get('/cms/footer-links', FooterLinksIndex::class)->name('cms.footer-links');
        Route::get('/cms/home/slides', HomeSlidesIndex::class)->name('cms.home.slides');
        Route::get('/cms/home/stats', HomeStatsIndex::class)->name('cms.home.stats');
        Route::get('/cms/home/quick-access', HomeQuickAccessIndex::class)->name('cms.home.quick-access');
        Route::get('/cms/home/testimonials', HomeTestimonialsIndex::class)->name('cms.home.testimonials');
        Route::get('/cms/announcements', AnnouncementsIndex::class)->name('cms.announcements.index');

        // CMS — about module
        Route::get('/cms/about/mission', MissionEdit::class)->name('cms.about.mission');
        Route::get('/cms/about/leadership', LeadershipIndex::class)->name('cms.about.leadership');
        Route::get('/cms/about/founding-members', FoundingMembersIndex::class)->name('cms.about.founding-members');
        Route::get('/cms/about/history', HistorySectionsIndex::class)->name('cms.about.history');
        Route::get('/cms/about/achievements', AchievementsIndex::class)->name('cms.about.achievements');
        Route::get('/cms/about/team', StaffIndex::class)->name('cms.about.team');

        // CMS — events module
        Route::get('/cms/events', EventsIndex::class)->name('cms.events.index');

        // CMS — academics module
        Route::get('/cms/academics/overview', AcademicsOverviewEdit::class)->name('cms.academics.overview');
        Route::get('/cms/academics/levels', AcademicLevelsIndex::class)->name('cms.academics.levels');

        // CMS — student life module
        Route::get('/cms/student-life/clubs',          ClubsIndex::class)->name('cms.student-life.clubs');
        Route::get('/cms/student-life/prefects',       PrefectsIndex::class)->name('cms.student-life.prefects');
        Route::get('/cms/student-life/houses',         HousesIndex::class)->name('cms.student-life.houses');
        Route::get('/cms/student-life/uniform-bodies', UniformBodiesIndex::class)->name('cms.student-life.uniform-bodies');
        Route::get('/cms/student-life/people',         PeopleIndex::class)->name('cms.student-life.people');

        // CMS — gallery module
        Route::get('/cms/gallery', GalleryIndex::class)->name('cms.gallery.index');

        // CMS — digital services module
        Route::get('/cms/digital-services/documents',       DocumentsIndex::class)->name('cms.digital-services.documents');
        Route::get('/cms/digital-services/resources',       ResourcesIndex::class)->name('cms.digital-services.resources');
        Route::get('/cms/digital-services/calendar',        CalendarEntriesIndex::class)->name('cms.digital-services.calendar');
        Route::redirect('/cms/digital-services/calendars',   '/cms/digital-services/calendar')->name('cms.digital-services.calendars');
    });
});

// Locale
Route::get('/lang/{locale}', [LocaleController::class, 'switch'])->name('lang.switch');

// Google OAuth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Public website
Route::get('/', [WebsiteController::class, 'home'])->name('home');
Route::get('/site-search', [WebsiteController::class, 'search'])->name('website.search');
Route::get('/announcements', [WebsiteController::class, 'announcements'])->name('announcements.index');
Route::get('/announcements/{slug}', [WebsiteController::class, 'announcementShow'])->name('announcements.show');
Route::get('/about', [WebsiteController::class, 'about'])->name('about');
Route::get('/events', [WebsiteController::class, 'events'])->name('events.index');
Route::get('/events/{slug}', [WebsiteController::class, 'eventShow'])->name('events.show');
Route::get('/academics', [WebsiteController::class, 'academics'])->name('academics.index');
Route::get('/student-life', [WebsiteController::class, 'studentLife'])->name('student-life.index');
Route::get('/student-life/{type}/{id}', [WebsiteController::class, 'studentLifeShow'])
    ->whereIn('type', ['clubs', 'houses', 'prefects', 'uniform-bodies'])
    ->whereNumber('id')
    ->name('student-life.show');
Route::get('/digital-services', [WebsiteController::class, 'digitalServices'])->name('digital-services.index');
Route::get('/admissions', fn () => 'Admissions')->name('admissions');
