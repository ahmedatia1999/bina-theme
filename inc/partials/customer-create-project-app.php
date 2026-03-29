<?php
/**
 * Customer create / edit project wizard.
 *
 * @var string   $back_url
 * @var string   $nonce
 * @var string[] $categories
 * @var string[] $reminders
 * @var array    $cities
 * @var bool     $is_edit
 * @var int      $edit_post_id
 * @var array    $prefill title, description, category, reminder, city, neighborhood, street, start_timing, has_plans, has_photos
 * @var int[]    $plans_attachment_ids
 * @var int[]    $site_photos_attachment_ids
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$is_edit     = ! empty( $is_edit );
$edit_post_id = isset( $edit_post_id ) ? absint( $edit_post_id ) : 0;
$prefill     = isset( $prefill ) && is_array( $prefill ) ? $prefill : array();

$def = function ( $key ) use ( $prefill ) {
	return isset( $prefill[ $key ] ) ? (string) $prefill[ $key ] : '';
};

$first_cat = $def( 'category' );
if ( $first_cat === '' && ! empty( $categories[0] ) ) {
	$first_cat = $categories[0];
}

$mode = $is_edit ? 'edit' : 'create';

$plans_attachment_ids       = isset( $plans_attachment_ids ) && is_array( $plans_attachment_ids ) ? array_map( 'absint', $plans_attachment_ids ) : array();
$site_photos_attachment_ids = isset( $site_photos_attachment_ids ) && is_array( $site_photos_attachment_ids ) ? array_map( 'absint', $site_photos_attachment_ids ) : array();

$has_plans_yes  = ( $def( 'has_plans' ) === 'نعم' );
$has_photos_yes = ( $def( 'has_photos' ) === 'نعم' );
?>
<div data-bina-create-project data-bina-mode="<?php echo esc_attr( $mode ); ?>" class="page-container mx-auto w-full max-w-4xl space-y-6">
	<a class="inline-flex items-center gap-2 text-sm font-medium hover:bg-accent rounded-md px-4 py-2 ps-0 mb-2" href="<?php echo esc_url( $back_url ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4" style="transform:rotate(180deg)"><path d="m12 19-7-7 7-7"></path><path d="M19 12H5"></path></svg>
		<?php esc_html_e( 'العودة إلى المشاريع', 'bina' ); ?>
	</a>

	<div class="flex items-center justify-center gap-2">
		<div class="flex items-center gap-2">
			<div id="bina-step-badge-1" class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-medium <?php echo $is_edit ? 'bg-muted text-muted-foreground' : 'bg-primary text-primary-foreground'; ?>">1</div>
			<span class="text-sm font-medium"><?php esc_html_e( 'اختر الفئة', 'bina' ); ?></span>
		</div>
		<div class="h-px w-12 bg-border"></div>
		<div class="flex items-center gap-2">
			<div id="bina-step-badge-2" class="flex h-8 w-8 items-center justify-center rounded-full text-sm font-medium <?php echo $is_edit ? 'bg-primary text-primary-foreground' : 'bg-muted text-muted-foreground'; ?>">2</div>
			<span class="text-sm font-medium"><?php esc_html_e( 'تفاصيل المشروع', 'bina' ); ?></span>
		</div>
	</div>

	<div id="bina-step1" class="space-y-6" <?php echo $is_edit ? 'style="display:none"' : ''; ?>>
		<div class="text-center">
			<h2 class="text-3xl font-bold tracking-tight"><?php esc_html_e( 'اختر فئتك', 'bina' ); ?></h2>
			<p class="mt-2 text-muted-foreground"><?php esc_html_e( 'اختر الفئة التي تناسب مشروعك بشكل أفضل', 'bina' ); ?></p>
		</div>
		<div class="grid gap-4 sm:grid-cols-2" role="radiogroup" aria-label="<?php esc_attr_e( 'اختر الفئة', 'bina' ); ?>">
			<?php
			foreach ( $categories as $idx => $cat_label ) {
				$is_selected = ( $cat_label === $first_cat );
				$card_class  = $is_selected
					? 'border-primary bg-primary/5 ring-2 ring-primary'
					: 'bg-card hover:border-primary';
				?>
				<div data-bina-category-card data-category="<?php echo esc_attr( $cat_label ); ?>"
					class="text-card-foreground flex flex-col gap-6 rounded-xl border py-6 shadow-sm cursor-pointer transition-all <?php echo esc_attr( $card_class ); ?>"
					role="radio" aria-checked="<?php echo $is_selected ? 'true' : 'false'; ?>" tabindex="0">
					<div class="px-6">
						<div class="flex items-start justify-between gap-2">
							<div class="font-semibold leading-none"><?php echo esc_html( $cat_label ); ?></div>
							<div class="flex h-6 w-6 items-center justify-center rounded-full bg-primary <?php echo $is_selected ? '' : 'hidden'; ?>" data-selected-check>
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="h-4 w-4 text-primary-foreground"><path d="M20 6 9 17l-5-5"></path></svg>
							</div>
						</div>
					</div>
				</div>
				<?php
			}
			?>
		</div>
		<div class="flex justify-start">
			<button type="button" id="bina-next-step" class="inline-flex items-center justify-center gap-2 rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-10 px-6 text-sm font-medium">
				<?php esc_html_e( 'التالي', 'bina' ); ?>
			</button>
		</div>
	</div>

	<div id="bina-step2" class="space-y-6" <?php echo $is_edit ? '' : 'style="display:none"'; ?>>
		<form id="bina-project-form-el" data-bina-project-form data-nonce="<?php echo esc_attr( $nonce ); ?>" data-bina-mode="<?php echo esc_attr( $mode ); ?>" <?php echo $is_edit && $edit_post_id ? 'data-post-id="' . esc_attr( (string) $edit_post_id ) . '"' : ''; ?> class="space-y-6" enctype="multipart/form-data" method="post">
			<?php if ( $is_edit && $edit_post_id ) : ?>
				<input type="hidden" name="post_id" id="bina-post-id" value="<?php echo esc_attr( (string) $edit_post_id ); ?>">
			<?php endif; ?>
			<input type="hidden" name="category" id="bina-category-hidden" value="<?php echo esc_attr( $first_cat ); ?>">
			<div class="text-center space-y-2 pt-4">
				<h1 class="text-3xl font-bold tracking-tight"><?php echo $is_edit ? esc_html__( 'تعديل المشروع', 'bina' ) : esc_html__( 'تفاصيل المشروع', 'bina' ); ?></h1>
				<p class="text-muted-foreground"><?php echo $is_edit ? esc_html__( 'حدّث بيانات مشروعك', 'bina' ) : esc_html__( 'أضف تفاصيل مشروعك', 'bina' ); ?></p>
			</div>
			<div class="bg-card text-card-foreground rounded-xl border py-6 shadow-sm">
				<div class="space-y-6 p-4 md:p-6">
					<h3 class="text-lg font-semibold"><?php esc_html_e( 'المعلومات الأساسية', 'bina' ); ?></h3>
					<div class="space-y-2">
						<label class="text-sm font-medium" for="bina-proj-title"><?php esc_html_e( 'اسم المشروع', 'bina' ); ?> <span class="text-destructive">*</span></label>
						<input class="border-input h-9 w-full rounded-md border bg-transparent px-3 py-1 text-sm" id="bina-proj-title" name="title" type="text" required placeholder="<?php esc_attr_e( 'أدخل اسم المشروع', 'bina' ); ?>" value="<?php echo esc_attr( $def( 'title' ) ); ?>">
					</div>
					<div class="space-y-2">
						<label class="text-sm font-medium" for="bina-proj-desc"><?php esc_html_e( 'الوصف', 'bina' ); ?> <span class="text-destructive">*</span></label>
						<textarea class="border-input min-h-24 w-full rounded-md border bg-transparent px-3 py-2 text-sm" id="bina-proj-desc" name="description" rows="4" required placeholder="<?php esc_attr_e( 'اوصف مشروعك', 'bina' ); ?>"><?php echo esc_textarea( $def( 'description' ) ); ?></textarea>
					</div>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
						<div class="space-y-2">
							<label class="text-sm font-medium" for="bina-reminder"><?php esc_html_e( 'موعد التذكير', 'bina' ); ?></label>
							<select class="border-input h-9 w-full rounded-md border bg-transparent px-3 text-sm" id="bina-reminder" name="reminder">
								<option value=""><?php esc_html_e( '— اختياري —', 'bina' ); ?></option>
								<?php foreach ( $reminders as $r ) : ?>
									<option value="<?php echo esc_attr( $r ); ?>" <?php selected( $def( 'reminder' ), $r ); ?>><?php echo esc_html( $r ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="space-y-2">
							<label class="text-sm font-medium" for="bina-city"><?php esc_html_e( 'المدينة', 'bina' ); ?></label>
							<select class="border-input h-9 w-full rounded-md border bg-transparent px-3 text-sm" id="bina-city" name="city">
								<option value=""><?php esc_html_e( '— اختياري —', 'bina' ); ?></option>
								<?php foreach ( $cities as $c ) : ?>
									<option value="<?php echo esc_attr( $c['value'] ); ?>" <?php selected( $def( 'city' ), $c['value'] ); ?>><?php echo esc_html( $c['label'] ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
					<div class="space-y-2">
						<label class="text-sm font-medium" for="bina-neighborhood"><?php esc_html_e( 'الحي', 'bina' ); ?></label>
						<input class="border-input h-9 w-full rounded-md border bg-transparent px-3 text-sm" id="bina-neighborhood" name="neighborhood" type="text" placeholder="<?php esc_attr_e( 'اسم الحي', 'bina' ); ?>" value="<?php echo esc_attr( $def( 'neighborhood' ) ); ?>">
					</div>
					<div class="space-y-2">
						<label class="text-sm font-medium" for="bina-street"><?php esc_html_e( 'الشارع', 'bina' ); ?></label>
						<input class="border-input h-9 w-full rounded-md border bg-transparent px-3 text-sm" id="bina-street" name="street" type="text" placeholder="<?php esc_attr_e( 'اختياري', 'bina' ); ?>" value="<?php echo esc_attr( $def( 'street' ) ); ?>">
					</div>
					<div class="space-y-2">
						<span class="text-sm font-medium"><?php esc_html_e( 'متى تتوقع البدء؟', 'bina' ); ?></span>
						<?php
						$st_vals = array( __( 'فوراً', 'bina' ), __( 'خلال شهر', 'bina' ), __( 'لاحقاً', 'bina' ) );
						foreach ( $st_vals as $stv ) {
							$rid = 'bina-st-' . md5( $stv );
							?>
							<label class="flex items-center gap-2 text-sm" for="<?php echo esc_attr( $rid ); ?>">
								<input type="radio" name="start_timing" id="<?php echo esc_attr( $rid ); ?>" value="<?php echo esc_attr( $stv ); ?>" <?php checked( $def( 'start_timing' ), $stv ); ?>>
								<?php echo esc_html( $stv ); ?>
							</label>
							<?php
						}
						?>
					</div>
					<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
						<div class="space-y-2" data-bina-plans-group>
							<span class="text-sm font-medium"><?php esc_html_e( 'هل لديك مخططات هندسية؟', 'bina' ); ?></span>
							<label class="flex items-center gap-2 text-sm"><input type="radio" name="has_plans" value="نعم" data-bina-toggle-plans="1" <?php checked( $def( 'has_plans' ), 'نعم' ); ?>> <?php esc_html_e( 'نعم', 'bina' ); ?></label>
							<label class="flex items-center gap-2 text-sm"><input type="radio" name="has_plans" value="لا" data-bina-toggle-plans="0" <?php checked( $def( 'has_plans' ), 'لا' ); ?>> <?php esc_html_e( 'لا', 'bina' ); ?></label>
						</div>
						<div class="space-y-2" data-bina-photos-group>
							<span class="text-sm font-medium"><?php esc_html_e( 'هل لديك صور للموقع؟', 'bina' ); ?></span>
							<label class="flex items-center gap-2 text-sm"><input type="radio" name="has_photos" value="نعم" data-bina-toggle-photos="1" <?php checked( $def( 'has_photos' ), 'نعم' ); ?>> <?php esc_html_e( 'نعم', 'bina' ); ?></label>
							<label class="flex items-center gap-2 text-sm"><input type="radio" name="has_photos" value="لا" data-bina-toggle-photos="0" <?php checked( $def( 'has_photos' ), 'لا' ); ?>> <?php esc_html_e( 'لا', 'bina' ); ?></label>
						</div>
					</div>

					<div id="bina-upload-plans-wrap" class="space-y-2 rounded-lg border border-dashed border-border bg-muted/30 p-4 <?php echo $has_plans_yes ? '' : 'hidden'; ?>" data-bina-upload-plans>
						<label class="text-sm font-medium" for="bina-plans-files"><?php esc_html_e( 'رفع مخططات (PDF أو صور)', 'bina' ); ?></label>
						<p class="text-xs text-muted-foreground"><?php esc_html_e( 'حد أقصى 15 ملفًا، 5 ميجابايت لكل ملف — PDF، JPG، PNG، WebP', 'bina' ); ?></p>
						<input class="block w-full text-sm file:me-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-foreground" type="file" id="bina-plans-files" name="bina_plans[]" multiple accept=".pdf,.jpg,.jpeg,.png,.webp,application/pdf,image/*">
						<?php if ( $is_edit && ! empty( $plans_attachment_ids ) ) : ?>
							<div class="mt-3 space-y-2">
								<p class="text-xs font-medium text-muted-foreground"><?php esc_html_e( 'المرفقات الحالية (يمكنك إضافة المزيد)', 'bina' ); ?></p>
								<ul class="flex flex-wrap gap-2 text-sm">
									<?php foreach ( $plans_attachment_ids as $aid ) : ?>
										<?php
										$url = wp_get_attachment_url( $aid );
										if ( ! $url ) {
											continue;
										}
										$mime = get_post_mime_type( $aid );
										?>
										<li class="rounded border bg-card px-2 py-1">
											<?php if ( $mime && strpos( $mime, 'image/' ) === 0 ) : ?>
												<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="inline-block"><?php echo wp_get_attachment_image( $aid, 'thumbnail', false, array( 'class' => 'h-12 w-auto rounded' ) ); ?></a>
											<?php else : ?>
												<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="text-primary underline"><?php echo esc_html( get_the_title( $aid ) ?: __( 'مرفق', 'bina' ) ); ?></a>
											<?php endif; ?>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
						<?php endif; ?>
					</div>

					<div id="bina-upload-photos-wrap" class="space-y-2 rounded-lg border border-dashed border-border bg-muted/30 p-4 <?php echo $has_photos_yes ? '' : 'hidden'; ?>" data-bina-upload-photos>
						<label class="text-sm font-medium" for="bina-site-photos-files"><?php esc_html_e( 'رفع صور الموقع', 'bina' ); ?></label>
						<p class="text-xs text-muted-foreground"><?php esc_html_e( 'حد أقصى 15 صورة، 5 ميجابايت لكل ملف — JPG، PNG، WebP، GIF', 'bina' ); ?></p>
						<input class="block w-full text-sm file:me-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-foreground" type="file" id="bina-site-photos-files" name="bina_site_photos[]" multiple accept=".jpg,.jpeg,.png,.webp,.gif,image/*">
						<?php if ( $is_edit && ! empty( $site_photos_attachment_ids ) ) : ?>
							<div class="mt-3 flex flex-wrap gap-2">
								<?php foreach ( $site_photos_attachment_ids as $aid ) : ?>
									<?php
									$url = wp_get_attachment_url( $aid );
									if ( ! $url ) {
										continue;
									}
									?>
									<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" class="block overflow-hidden rounded border"><?php echo wp_get_attachment_image( $aid, 'thumbnail', false, array( 'class' => 'h-16 w-16 object-cover' ) ); ?></a>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="pt-2">
						<button type="submit" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground shadow-xs hover:bg-primary/90 h-10 px-8 text-sm font-medium w-full sm:w-auto">
							<?php echo $is_edit ? esc_html__( 'حفظ التعديلات', 'bina' ) : esc_html__( 'حفظ وإنشاء المشروع', 'bina' ); ?>
						</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
