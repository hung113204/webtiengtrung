

<?php $__env->startSection('title', 'Quáº£n lÃ½ NgÆ°á»i dÃ¹ng â€” HÃ¡nyÇ” Admin'); ?>

<?php $__env->startSection('content'); ?>
      <div class="page-header animate-fade-in delay-1">
        <div>
          <h1 class="fs-4 fw-bold mb-1">Quáº£n lÃ½ ngÆ°á»i dÃ¹ng</h1>
          <p class="text-muted mb-0 small">Quáº£n lÃ½ danh sÃ¡ch há»c viÃªn, giÃ¡o viÃªn vÃ  quyá»n truy cáº­p há»‡ thá»‘ng.</p>
        </div>
        <button class="btn btn-primary d-flex align-items-center gap-2 shadow-sm" style="background: var(--admin-primary); border: none;" data-bs-toggle="modal" data-bs-target="#createModal">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
          ThÃªm ngÆ°á»i dÃ¹ng má»›i
        </button>
      </div>

      <!-- Data Table Card -->
      <div class="table-card animate-fade-in delay-2">
        <form action="<?php echo e(route('admin.nguoidung.index')); ?>" method="GET" class="table-header d-flex flex-wrap gap-3 p-3 border-bottom">
          <div class="input-group" style="max-width: 300px;">
            <span class="input-group-text bg-white border-end-0 text-muted">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </span>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" class="form-control border-start-0 ps-0" placeholder="TÃ¬m kiáº¿m tÃªn, email, sÄ‘t...">
          </div>
          
          <div class="d-flex gap-2 ms-auto">
            <select name="role_id" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
              <option value="">Táº¥t cáº£ vai trÃ²</option>
              <?php $__currentLoopData = $vaiTros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($vt->id); ?>" <?php echo e(request('role_id') == $vt->id ? 'selected' : ''); ?>><?php echo e($vt->ten_vai_tro); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
              <option value="">Tráº¡ng thÃ¡i</option>
              <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Äang hoáº¡t Ä‘á»™ng</option>
              <option value="banned" <?php echo e(request('status') === 'banned' ? 'selected' : ''); ?>>Bá»‹ khÃ³a</option>
            </select>
            <button type="submit" class="d-none"></button>
          </div>
        </form>

        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light text-muted small">
              <tr>
                <th class="fw-medium px-4 py-3">NgÆ°á»i dÃ¹ng</th>
                <th class="fw-medium py-3">Vai trÃ²</th>
                <th class="fw-medium py-3">NgÃ y tham gia</th>
                <th class="fw-medium py-3">ÄÄƒng nháº­p cuá»‘i</th>
                <th class="fw-medium py-3">Tráº¡ng thÃ¡i</th>
                <th class="fw-medium pe-4 py-3 text-end">Thao tÃ¡c</th>
              </tr>
            </thead>
            <tbody>
              <?php $__empty_1 = true; $__currentLoopData = $nguoidungs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td class="px-4 py-3">
                  <div class="d-flex align-items-center gap-3">
                    <img src="<?php echo e($item->anh_dai_dien ? Storage::url($item->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode($item->ho_ten).'&background=random'); ?>" class="rounded-circle border border-2 border-white shadow-sm" width="42" height="42" style="object-fit: cover;" alt="Avatar">
                    <div>
                      <div class="fw-semibold text-dark"><?php echo e($item->ho_ten); ?></div>
                      <div class="small text-muted"><?php echo e($item->email); ?></div>
                    </div>
                  </div>
                </td>
                <td>
                    <?php if($item->vaiTro && $item->vaiTro->ten_vai_tro === 'Admin'): ?>
                        <span class="badge bg-danger"><?php echo e($item->vaiTro->ten_vai_tro); ?></span>
                    <?php elseif($item->vaiTro && ($item->vaiTro->ten_vai_tro === 'Giáº£ng viÃªn' || $item->vaiTro->ten_vai_tro === 'GiÃ¡o viÃªn')): ?>
                        <span class="badge" style="background-color: var(--admin-primary); opacity: 0.9;"><?php echo e($item->vaiTro->ten_vai_tro); ?></span>
                    <?php else: ?>
                        <span class="badge bg-light text-dark border"><?php echo e($item->vaiTro->ten_vai_tro ?? 'Há»c viÃªn'); ?></span>
                    <?php endif; ?>
                </td>
                <td class="small text-muted"><?php echo e($item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') : 'N/A'); ?></td>
                <td class="small text-muted">
                    <?php if($item->last_login_at): ?>
                        <span title="<?php echo e(\Carbon\Carbon::parse($item->last_login_at)->format('d/m/Y H:i')); ?>"><?php echo e(\Carbon\Carbon::parse($item->last_login_at)->diffForHumans()); ?></span>
                    <?php else: ?>
                        <span class="text-muted fst-italic">ChÆ°a Ä‘Äƒng nháº­p</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($item->trang_thai): ?>
                        <span class="badge bg-success bg-opacity-10 text-success border border-success-subtle">Hoáº¡t Ä‘á»™ng</span>
                    <?php elseif($item->user_token): ?>
                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning-subtle">Chá» xÃ¡c thá»±c</span>
                    <?php else: ?>
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle">Bá»‹ khÃ³a</span>
                    <?php endif; ?>
                </td>
                <td class="text-end pe-4">
                  <div class="d-flex justify-content-end gap-1">
                    <button class="icon-btn text-info" title="Xem chi tiáº¿t" data-bs-toggle="modal" data-bs-target="#showModal_<?php echo e($item->id); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </button>
                    <button class="icon-btn text-primary" title="Chá»‰nh sá»­a" data-bs-toggle="modal" data-bs-target="#editModal_<?php echo e($item->id); ?>"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></button>
                    <form action="<?php echo e(route('admin.nguoidung.destroy', $item->id)); ?>" method="POST" style="display:inline; margin:0; padding:0;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="button" class="icon-btn text-danger btn-delete" title="XÃ³a ngÆ°á»i dÃ¹ng" onclick="deleteDataAjax(<?php echo e($item->id); ?>, '<?php echo e(addslashes($item->ho_ten)); ?>', '<?php echo e(route('admin.nguoidung.destroy', $item->id)); ?>')" style="background:none; border:none;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6" /><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" /></svg>
                        </button>
                    </form>
                  </div>
                </td>
              </tr>

              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                  <td colspan="5" class="text-center py-4 text-muted">ChÆ°a cÃ³ ngÆ°á»i dÃ¹ng nÃ o trong há»‡ thá»‘ng.</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        </div>
        <?php if($nguoidungs->hasPages()): ?>
        <div class="p-3 border-top d-flex justify-content-end">
            <?php echo e($nguoidungs->links('pagination::bootstrap-5')); ?>

        </div>
        <?php endif; ?>
      </div>

      <!-- Danh sÃ¡ch Modal Sá»­a NgÆ°á»i dÃ¹ng (Ä‘áº·t ngoÃ i table Ä‘á»ƒ khÃ´ng bá»‹ lá»—i hiá»ƒn thá»‹ CSS) -->
      <?php $__currentLoopData = $nguoidungs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      
      <!-- Modal Xem chi tiáº¿t -->
      <div class="modal fade" id="showModal_<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Chi tiáº¿t ngÆ°á»i dÃ¹ng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-2">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    <img src="<?php echo e($item->anh_dai_dien ? Storage::url($item->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode($item->ho_ten).'&background=random'); ?>" class="rounded-circle border border-2 border-white shadow-sm" width="70" height="70" style="object-fit: cover;" alt="Avatar">
                    <div>
                        <h5 class="mb-1 fw-bold"><?php echo e($item->ho_ten); ?></h5>
                        <div class="mb-1">
                            <span class="badge <?php echo e($item->vaiTro && $item->vaiTro->ten_vai_tro === 'Admin' ? 'bg-danger' : ($item->vaiTro && ($item->vaiTro->ten_vai_tro === 'Giáº£ng viÃªn' || $item->vaiTro->ten_vai_tro === 'GiÃ¡o viÃªn') ? 'bg-primary' : 'bg-secondary')); ?>">
                                <?php echo e($item->vaiTro->ten_vai_tro ?? 'Há»c viÃªn'); ?>

                            </span>
                            <?php if($item->trang_thai): ?>
                                <span class="badge bg-success">Hoáº¡t Ä‘á»™ng</span>
                            <?php elseif($item->user_token): ?>
                                <span class="badge bg-warning text-dark">Chá» xÃ¡c thá»±c</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Bá»‹ khÃ³a</span>
                            <?php endif; ?>
                        </div>
                        <div class="text-muted small"><i class="fas fa-envelope me-1"></i> <?php echo e($item->email); ?></div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">ThÃ´ng tin cÆ¡ báº£n</h6>
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="text-muted small">TÃªn Ä‘Äƒng nháº­p</div>
                        <div class="fw-medium"><?php echo e($item->ten_dang_nhap); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Sá»‘ Ä‘iá»‡n thoáº¡i</div>
                        <div class="fw-medium"><?php echo e($item->so_dien_thoai ?? 'ChÆ°a cáº­p nháº­t'); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">Giá»›i tÃ­nh</div>
                        <div class="fw-medium"><?php echo e($item->gioi_tinh ?? 'ChÆ°a cáº­p nháº­t'); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">NgÃ y sinh</div>
                        <div class="fw-medium"><?php echo e($item->ngay_sinh ? \Carbon\Carbon::parse($item->ngay_sinh)->format('d/m/Y') : 'ChÆ°a cáº­p nháº­t'); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">NgÃ y tham gia</div>
                        <div class="fw-medium"><?php echo e($item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') : 'N/A'); ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-muted small">ÄÄƒng nháº­p láº§n cuá»‘i</div>
                        <div class="fw-medium"><?php echo e($item->last_login_at ? \Carbon\Carbon::parse($item->last_login_at)->format('d/m/Y H:i') : 'ChÆ°a Ä‘Äƒng nháº­p'); ?></div>
                    </div>
                </div>

                <?php if(!$item->vaiTro || mb_strtolower($item->vaiTro->ten_vai_tro, 'UTF-8') === 'há»c viÃªn'): ?>
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Há»“ sÆ¡ há»c táº­p</h6>
                <?php if($item->hoSoHocVien): ?>
                <div class="row g-3 mb-4">
                    <div class="col-sm-4">
                        <div class="text-muted small">TrÃ¬nh Ä‘á»™ hiá»‡n táº¡i</div>
                        <div class="fw-medium text-primary"><?php echo e($item->hoSoHocVien->trinh_do_hien_tai ?? 'ChÆ°a cÃ³'); ?></div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">Má»¥c tiÃªu há»c táº­p</div>
                        <div class="fw-medium text-danger"><?php echo e($item->hoSoHocVien->muc_tieu_hoc_tap ?? 'ChÆ°a cÃ³'); ?></div>
                    </div>
                    <div class="col-sm-4">
                        <div class="text-muted small">Thá»i gian dá»± kiáº¿n</div>
                        <div class="fw-medium"><?php echo e($item->hoSoHocVien->thoi_gian_hoc_du_kien ?? 'ChÆ°a cÃ³'); ?></div>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-light text-muted small py-2 mb-4">
                    Há»c viÃªn chÆ°a cáº­p nháº­t há»“ sÆ¡ há»c táº­p.
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <?php if($item->ghi_chu): ?>
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Ghi chÃº ná»™i bá»™</h6>
                <div class="bg-light p-3 rounded-2 text-muted small">
                    <?php echo e($item->ghi_chu); ?>

                </div>
                <?php endif; ?>
              </div>
              <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">ÄÃ³ng</button>
                <button type="button" class="btn btn-primary px-4" data-bs-toggle="modal" data-bs-target="#editModal_<?php echo e($item->id); ?>">Chá»‰nh sá»­a</button>
              </div>
            </div>
        </div>
      </div>

      <div class="modal fade" id="editModal_<?php echo e($item->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <form action="<?php echo e(route('admin.nguoidung.update', $item->id)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
              <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold">Chá»‰nh sá»­a ngÆ°á»i dÃ¹ng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start pt-2">
                <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                    <img src="<?php echo e($item->anh_dai_dien ? Storage::url($item->anh_dai_dien) : 'https://ui-avatars.com/api/?name='.urlencode($item->ho_ten).'&background=random'); ?>" class="rounded-circle border border-2 border-white shadow-sm" width="55" height="55" style="object-fit: cover;" alt="Avatar">
                    <div>
                        <h6 class="mb-0 fw-bold"><?php echo e($item->ho_ten); ?></h6>
                        <span class="badge <?php echo e($item->vaiTro && $item->vaiTro->ten_vai_tro === 'Admin' ? 'bg-danger' : ($item->vaiTro && ($item->vaiTro->ten_vai_tro === 'Giáº£ng viÃªn' || $item->vaiTro->ten_vai_tro === 'GiÃ¡o viÃªn') ? 'bg-primary' : 'bg-secondary')); ?> mt-1">
                            <?php echo e($item->vaiTro->ten_vai_tro ?? 'Há»c viÃªn'); ?>

                        </span>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Há» vÃ  tÃªn <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light" name="ho_ten" value="<?php echo e($item->ho_ten); ?>" autocomplete="off" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">áº¢nh Ä‘áº¡i diá»‡n</label>
                        <input type="file" class="form-control bg-light" name="anh_dai_dien" accept="image/*">
                        <?php if($item->anh_dai_dien): ?>
                            <div class="mt-2">
                                <img src="<?php echo e(Storage::url($item->anh_dai_dien)); ?>" alt="Avatar" class="rounded-circle border" width="40" height="40" style="object-fit: cover;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">TÃªn Ä‘Äƒng nháº­p <span class="text-danger">*</span></label>
                        <input type="text" class="form-control bg-light" name="ten_dang_nhap" value="<?php echo e($item->ten_dang_nhap); ?>" autocomplete="off" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Email liÃªn há»‡ <span class="text-danger">*</span></label>
                        <input type="email" class="form-control bg-light" name="email" value="<?php echo e($item->email); ?>" autocomplete="off" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Sá»‘ Ä‘iá»‡n thoáº¡i</label>
                        <input type="text" class="form-control bg-light" name="so_dien_thoai" value="<?php echo e($item->so_dien_thoai); ?>" placeholder="ChÆ°a cáº­p nháº­t">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">NgÃ y sinh</label>
                        <input type="date" class="form-control bg-light" name="ngay_sinh" value="<?php echo e($item->ngay_sinh ? (is_string($item->ngay_sinh) ? $item->ngay_sinh : $item->ngay_sinh->format('Y-m-d')) : ''); ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Máº­t kháº©u má»›i</label>
                        <div class="input-group">
                            <input type="password" class="form-control bg-light" name="mat_khau" placeholder="Äá»ƒ trá»‘ng náº¿u khÃ´ng Ä‘á»•i" autocomplete="new-password">
                        </div>
                        <small class="text-muted mt-1 d-block">Chá»‰ nháº­p khi muá»‘n Ä‘á»•i máº­t kháº©u má»›i.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">Giá»›i tÃ­nh</label>
                        <select class="form-select bg-light" name="gioi_tinh">
                            <option value="">-- ChÆ°a cáº­p nháº­t --</option>
                            <option value="Nam" <?php echo e($item->gioi_tinh == 'Nam' ? 'selected' : ''); ?>>Nam</option>
                            <option value="Ná»¯" <?php echo e($item->gioi_tinh == 'Ná»¯' ? 'selected' : ''); ?>>Ná»¯</option>
                            <option value="KhÃ¡c" <?php echo e($item->gioi_tinh == 'KhÃ¡c' ? 'selected' : ''); ?>>KhÃ¡c</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">PhÃ¢n quyá»n vai trÃ² <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-primary" name="id_vai_tro" required>
                            <?php $__currentLoopData = $vaiTros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($vt->id); ?>" data-role="<?php echo e(mb_strtolower($vt->ten_vai_tro, 'UTF-8')); ?>" <?php echo e($item->id_vai_tro == $vt->id ? 'selected' : ''); ?>>
                                    <?php echo e($vt->ten_vai_tro); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="form-check form-switch fs-5 mt-4">
                            <input type="hidden" name="trang_thai" value="0">
                            <input class="form-check-input" type="checkbox" role="switch" name="trang_thai" value="1" id="trang_thai_<?php echo e($item->id); ?>" <?php echo e($item->trang_thai ? 'checked' : ''); ?>>
                            <label class="form-check-label fs-6 ms-2" for="trang_thai_<?php echo e($item->id); ?>">
                                Cho phÃ©p Ä‘Äƒng nháº­p
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <label class="form-label fw-medium text-dark">Ghi chÃº ná»™i bá»™</label>
                        <textarea class="form-control bg-light" name="ghi_chu" rows="2" placeholder="Ghi chÃº dÃ nh cho quáº£n trá»‹ viÃªn, há»c viÃªn sáº½ khÃ´ng tháº¥y..."><?php echo e($item->ghi_chu); ?></textarea>
                    </div>
                </div>
              </div>
              <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Há»§y bá»</button>
                <button type="submit" class="btn btn-primary px-4" style="background: var(--admin-primary); border: none;">LÆ°u thay Ä‘á»•i</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

      <!-- Modal ThÃªm NgÆ°á»i dÃ¹ng -->
      <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <form action="<?php echo e(route('admin.nguoidung.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
              <div class="modal-header">
                <h5 class="modal-title fw-bold">ThÃªm NgÆ°á»i dÃ¹ng má»›i</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-start">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Há» vÃ  tÃªn <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ho_ten" placeholder="Nháº­p há» vÃ  tÃªn..." required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">TÃªn Ä‘Äƒng nháº­p <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="ten_dang_nhap" placeholder="VD: nguyenvanA" autocomplete="off" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">áº¢nh Ä‘áº¡i diá»‡n</label>
                        <input type="file" class="form-control" name="anh_dai_dien" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" placeholder="example@gmail.com" autocomplete="off" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Máº­t kháº©u <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="mat_khau" placeholder="Tá»‘i thiá»ƒu 6 kÃ½ tá»±" autocomplete="new-password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Sá»‘ Ä‘iá»‡n thoáº¡i</label>
                        <input type="text" class="form-control" name="so_dien_thoai" placeholder="Nháº­p sá»‘ Ä‘iá»‡n thoáº¡i...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">NgÃ y sinh</label>
                        <input type="date" class="form-control" name="ngay_sinh">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Giá»›i tÃ­nh</label>
                        <select class="form-select" name="gioi_tinh">
                            <option value="">-- Chá»n giá»›i tÃ­nh --</option>
                            <option value="Nam">Nam</option>
                            <option value="Ná»¯">Ná»¯</option>
                            <option value="KhÃ¡c">KhÃ¡c</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-medium text-dark">PhÃ¢n quyá»n vai trÃ² <span class="text-danger">*</span></label>
                        <select class="form-select bg-light border-primary" name="id_vai_tro" required>
                            <?php $__currentLoopData = $vaiTros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($vt->id); ?>" data-role="<?php echo e(mb_strtolower($vt->ten_vai_tro, 'UTF-8')); ?>"><?php echo e($vt->ten_vai_tro); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input type="hidden" name="trang_thai" value="0">
                            <input class="form-check-input" type="checkbox" name="trang_thai" value="1" id="trang_thai_new" checked>
                            <label class="form-check-label" for="trang_thai_new">
                                KÃ­ch hoáº¡t tÃ i khoáº£n
                            </label>
                        </div>
                    </div>
                    </div>
                    


                    
                    <div class="col-12">
                        <label class="form-label">Ghi chÃº ná»™i bá»™</label>
                        <textarea class="form-control" name="ghi_chu" rows="2" placeholder="Ghi chÃº dÃ nh cho quáº£n trá»‹ viÃªn, há»c viÃªn sáº½ khÃ´ng tháº¥y..."></textarea>
                    </div>
                </div>
              </div>
              <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Há»§y</button>
                <button type="submit" class="btn btn-primary" style="background: var(--admin-primary); border: none;">ThÃªm má»›i</button>
              </div>
            </form>
          </div>
        </div>
      </div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('admin.layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\webtiengtrung\resources\views/admin/nguoidung/index.blade.php ENDPATH**/ ?>
