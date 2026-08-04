<?php
$content = file_get_contents('c:/xampp/htdocs/webtiengtrung/restored_view.php');

$content = str_replace('<?php echo csrf_field(); ?>', '@csrf', $content);
$content = str_replace('<?php echo method_field(\'PUT\'); ?>', '@method(\'PUT\')', $content);
$content = preg_replace('/<\?php echo e\((.*?)\); \?>/', '{{ $1 }}', $content);
$content = preg_replace('/<\?php \$__env->startSection\(\'(.*?)\'(?:, \'(.*?)\')?\); \?>/', '@section(\'$1\'$2)', $content);
$content = str_replace('<?php $__env->stopSection(); ?>', '@endsection', $content);
$content = preg_replace('/<\?php if\((.*?)\): \?>/', '@if($1)', $content);
$content = preg_replace('/<\?php elseif\((.*?)\): \?>/', '@elseif($1)', $content);
$content = str_replace('<?php else: ?>', '@else', $content);
$content = str_replace('<?php endif; ?>', '@endif', $content);

$content = preg_replace('/<\?php \$__currentLoopData = (.*?); \$__env->addLoop.*?foreach.*?as (.*?)\):.*? \?>/', '@foreach($1 as $2)', $content);
$content = str_replace('<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>', '@endforeach', $content);

$content = preg_replace('/<\?php \$__empty_\d+ = true; \$__currentLoopData = (.*?); \$__env->addLoop.*?foreach.*?as (.*?)\):.*?\$__empty_\d+ = false; \?>/', '@forelse($1 as $2)', $content);
$content = preg_replace('/<\?php endforeach; \$__env->popLoop\(\); \$loop = \$__env->getLastLoop\(\); if \(\$__empty_\d+\): \?>/', '@empty', $content);
$content = str_replace('<?php endif; ?>', '@endforelse', $content); 

$content = str_replace('@empty'."\n".'              <tr>'."\n".'                  <td colspan="5" class="text-center py-4 text-muted">Chưa có ngư� i dùng nào trong hệ thống.</td>'."\n".'              </tr>'."\n".'              @endforelse'."\n".'            </tbody>', '@empty'."\n".'              <tr>'."\n".'                  <td colspan="5" class="text-center py-4 text-muted">Chưa có ngư� i dùng nào trong hệ thống.</td>'."\n".'              </tr>'."\n".'              @endforelse'."\n".'            </tbody>', $content);

// Let's manually fix the forelse end
$content = str_replace('@empty'."\n".'              <tr>'."\n".'                  <td colspan="5" class="text-center py-4 text-muted">Chưa có ngư� i dùng nào trong hệ thống.</td>'."\n".'              </tr>'."\n".'              @endif', '@empty'."\n".'              <tr>'."\n".'                  <td colspan="5" class="text-center py-4 text-muted">Chưa có ngư� i dùng nào trong hệ thống.</td>'."\n".'              </tr>'."\n".'              @endforelse', $content);

$content = preg_replace('/@endif(\s*)<\/tbody>/', '@endforelse$1</tbody>', $content);
$content = preg_replace('/<\?php echo \$__env->make.*?render\(\); \?>.*?$/s', '', $content);

file_put_contents('c:/xampp/htdocs/webtiengtrung/resources/views/admin/nguoidung/index.blade.php', $content);
echo "Reconstruction complete.";
?>
